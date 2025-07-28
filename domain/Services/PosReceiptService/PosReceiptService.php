<?php

namespace domain\Services\PosReceiptService;

use App\Models\BillPayment;
use App\Models\BusinessDetail;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\PosOrder;
use App\Models\PosReceipt;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use Illuminate\Support\Facades\Auth;

/**
 * PosReceiptService
 * php version 8
 *
 * @category Service
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class PosReceiptService
{
    protected $order;

    protected $pos_receipt;

    protected $bill_payment;

    protected $transaction;

    protected $customer;

    private $transaction_balance;

    private $currency;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->order = new PosOrder;
        $this->pos_receipt = new PosReceipt;
        $this->bill_payment = new BillPayment;
        $this->transaction = new Transaction;
        $this->customer = new Customer;
        $this->transaction_balance = new TransactionBalance;
        $this->currency = new Currency;
    }

    /**
     * All
     * retrieve all the data from PosReceipt model
     *
     * @return void
     */
    public function all()
    {
        return $this->pos_receipt->all();
    }

    /**
     * Store
     * store data in database
     *
     * @return void
     */
    public function store(array $data)
    {
        return $this->pos_receipt->create($data);
    }

    /**
     * Get
     * retrieve relevant data using pos_receipt_id
     *
     * @return void
     */
    public function get(int $pos_receipt_id)
    {
        return $this->pos_receipt->find($pos_receipt_id);
    }

    /**
     * GetBills
     * retrieve relevant data using order_id
     *
     * @param  int  $order_id
     * @return void
     */
    public function getBills($order_id)
    {
        return $this->bill_payment->where('order_id', $order_id)->where('accepted_amount', '>', 0)->orderBy('date', 'ASC')->get();
    }

    /**
     * Update
     * update existing data using pos_receipt_id
     *
     * @return void
     */
    public function update(array $data, int $pos_receipt_id)
    {
        $pos_receipt = $this->pos_receipt->find($pos_receipt_id);

        return $pos_receipt->update($this->edit($pos_receipt, $data));
    }

    public function creditPay(array $data, int $order_id)
    {
        if (isset($data['date'])) {
            $today = date('Y-m-d', strtotime($data['date']));
            $data['date'] = $today;
        } else {
            $today = \Carbon\Carbon::now();
            $data['date'] = $today->toDateString();
        }
        $data['created_by'] = Auth::user()->id;

        $bill_payment = $this->bill_payment->where('order_id', $order_id)->latest()->first();
        $paid_accepted_amount_total = $this->bill_payment->where('order_id', $order_id)->sum('paid_amount');
        $order = $this->order->find($order_id);
        $customer = $this->customer->where('id', $order->customer_id)->first();

        $count = $this->bill_payment->count();

        $code = 'B'.sprintf('%05d', $count + 1);
        $check = $this->bill_payment->getCode($code);

        while ($check) {
            $count++;
            $code = 'B'.sprintf('%05d', $count);
            $check = $this->bill_payment->getCode($code);
        }

        $data['code'] = $code;

        if ($order->type == $this->order::TYPE['INVOICE']) {
            $data['paid_amount'] = $data['balance'];
            $data['accepted_amount'] = $data['balance'];
            $order->customer_paid = $order->customer_paid + $data['balance'];
            $order->balance = 0;
        } else {
            if (($order->total - $order->customer_paid) >= $data['balance']) {
                $data['paid_amount'] = $data['balance'];
                // if (isset($customer->customer_outstanding)) {
                //     $customer->customer_outstanding = $customer->customer_outstanding - $data['balance'];
                // }
                $data['accepted_amount'] = $data['balance'];
                $order->customer_paid = $order->customer_paid + $data['balance'];
                $order->balance = 0;
            } else {
                $data['accepted_amount'] = $order->total - $order->customer_paid;
                // if (isset($customer->customer_outstanding)) {
                //     $customer->customer_outstanding = $customer->customer_outstanding - $data['accepted_amount'];
                // }
                $data['paid_amount'] = $data['balance'];
                $data['change'] = $data['balance'] - $data['accepted_amount'];
                $data['balance'] = 0;
                $order->customer_paid = $order->customer_paid + $data['accepted_amount'];
                $order->balance = $data['change'];
            }
        }

        if ($order->total <= $order->customer_paid) {
            $order->credit_status = 1;
            $order->status = 1;
        }
        if ($order->status == 2 && $data['balance'] > 0) {
            $order->status = 1;
        }

        $data['order_id'] = $order_id;
        $data['order_total'] = $order->total;
        $data['customer_id'] = $order->customer_id;

        // if (isset($customer)) {
        //     $customer->save();
        // }

        if ($order->currency_id == null) {
            $business_details = BusinessDetail::first();
            $order->currency_id = $business_details->currency_id;
        }

        $order->save();
        $updated_order = $this->order->where('id', $order_id)->first();
        if ($updated_order->total == $updated_order->customer_paid) {
            $updated_order->status = 1;
            $updated_order->save();
        }
        $created_bill = $this->bill_payment->create($data);

        // transaction log
        $transaction_count = $this->transaction->count();
        $tr_code = 'TR'.sprintf('%05d', $transaction_count + 1);
        $check_tr = $this->transaction->getCode($tr_code);

        while ($check_tr) {
            $transaction_count++;
            $tr_code = 'TR'.sprintf('%05d', $transaction_count);
            $check_tr = $this->transaction->getCode($tr_code);
        }

        $transaction_data['code'] = $tr_code;
        $transaction_data['date'] = $today;
        $transaction_data['reference_code'] = $updated_order->code;
        $transaction_data['payment_code'] = $created_bill->code;
        if ($updated_order->customer_id != null) {
            $transaction_data['customer_id'] = $updated_order->customer_id;
            if ($customer) {
                $transaction_data['client'] = $customer->name;
            } else {
                $transaction_data['client'] = 'Customer not available';
            }
        } else {
            $transaction_data['client'] = 'Walking Customer';
        }
        $transaction_data['currency_id'] = $updated_order->currency_id;
        $transaction_data['amount'] = $created_bill->accepted_amount;
        if ($order->type == 1) {
            $transaction_data['type'] = 2;
            $transaction_data['description'] = 'Invoice Payment';
            if (isset($data['remark'])) {
                $transaction_data['remark'] = $data['remark'];
            }
        } elseif ($order->type == 0) {
            $transaction_data['type'] = 1;
            $transaction_data['description'] = 'POS Credit Payment';
            if (isset($data['remark'])) {
                $transaction_data['remark'] = $data['remark'];
            }
        }
        $this->transaction->create($transaction_data);

        $transaction_balance = $this->transaction_balance->where('currency_id', $transaction_data['currency_id'])->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount + $created_bill->accepted_amount;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($transaction_data['currency_id']);
            $balance_data['currency_id'] = $transaction_data['currency_id'];
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = $created_bill->accepted_amount;
            $this->transaction_balance->create($balance_data);
        }
        // end transaction log
    }

    public function updateCreditPay(array $data)
    {
        $bill_payment = $this->bill_payment->where('id', $data['id'])->first();
        $paid_accepted_amount_total = $this->bill_payment->where('order_id', $data['order_id'])->sum('accepted_amount');
        $paid_another_total = $paid_accepted_amount_total - $bill_payment->paid_amount;
        $order = $this->order->where('id', $data['order_id'])->first();
        $customer = $this->customer->where('id', $order->customer_id)->first();

        if (isset($data['date'])) {
            $today = date('Y-m-d', strtotime($data['date']));
            $bill_payment->date = $today;
        }
        $bill_payment->created_by = Auth::user()->id;

        // if (($order->total - $order->customer_paid) >= $data['accepted_amount']) {
        //     $bill_payment->paid_amount = $data['accepted_amount'];
        //     $bill_payment->accepted_amount = $data['accepted_amount'];
        // } else {
        //     if ($bill_payment->accepted_amount >= $data['accepted_amount']) {
        //         $bill_payment->paid_amount = $data['accepted_amount'];
        //         $bill_payment->accepted_amount = $data['accepted_amount'];
        //     } else {
        //         $bill_payment->paid_amount = $data['accepted_amount'];
        //         $bill_payment->accepted_amount = $bill_payment->accepted_amount + $order->total - $order->customer_paid;
        //         if (isset($customer->customer_outstanding)) {
        //             $customer->customer_outstanding = $customer->customer_outstanding - $bill_payment->accepted_amount;
        //         }
        //     }
        // }

        $bill_payment->paid_amount = $data['accepted_amount'];
        $bill_payment->accepted_amount = $data['accepted_amount'];
        if (isset($customer->customer_outstanding)) {
            $customer->customer_outstanding = $customer->customer_outstanding - $bill_payment->accepted_amount;
        }

        $bill_payment->save();

        $new_paid_accepted_amount_total = $this->bill_payment->where('order_id', $data['order_id'])->sum('accepted_amount');
        $order->customer_paid = $new_paid_accepted_amount_total;

        if ($order->total <= $new_paid_accepted_amount_total) {
            $order->credit_status = 1;
        } else {
            $order->credit_status = 0;
        }

        if ($order->status = 2 && $data['accepted_amount'] > 0) {
            $order->status = 1;
        }

        if ($order->currency_id == null) {
            $business_details = BusinessDetail::first();
            $order->currency_id = $business_details->currency_id;
        }

        $order->save();

        // transaction log
        $created_transaction = $this->transaction->where('reference_code', $order->code)->where('payment_code', $bill_payment->code)->first();
        if ($created_transaction) {

            $business_detail = BusinessDetail::first();
            $business_detail->transaction_balance = $business_detail->transaction_balance + ($bill_payment->accepted_amount - $created_transaction->amount);
            $business_detail->save();

            $transaction_balance = $this->transaction_balance->where('currency_id', $order['currency_id'])->first();
            if ($transaction_balance) {
                $transaction_balance->amount = $transaction_balance->amount + ($bill_payment->accepted_amount - $created_transaction->amount);
                $transaction_balance->save();
            } else {
                $currency = $this->currency->find($order['currency_id']);
                $balance_data['currency_id'] = $order['currency_id'];
                $balance_data['code'] = $currency['code'];
                $balance_data['amount'] = $bill_payment->accepted_amount - $created_transaction->amount;
                $this->transaction_balance->create($balance_data);
            }

            $created_transaction->amount = $bill_payment->accepted_amount;
            $created_transaction->save();
        }

        // end transaction log
    }

    /**
     * Edit
     * merge data which retrieved from update function as an array
     *
     * @return void
     */
    protected function edit(PosReceipt $pos_receipt, array $data)
    {
        return array_merge($pos_receipt->toArray(), $data);
    }

    /**
     * Delete
     * delete specific data using pos_receipt_id
     *
     * @return void
     */
    public function delete(int $pos_receipt_id)
    {
        return $this->pos_receipt->find($pos_receipt_id)->delete();
    }

    public function editBill(int $bill_id)
    {
        return $this->bill_payment->find($bill_id);
    }

    public function storeTotal(int $id)
    {
        $order = $this->get($id);
        $order->total = $order->items->sum('total');
        $order->sub_total = $order->items->sum('total');
        $order->save();

        return $order;
    }

    public function holdOrders()
    {
        $user = Auth::user();

        return $this->pos_receipt->holds($user->name);
    }

    public function cancel(int $pos_receipt_id)
    {
        $order = $this->get($pos_receipt_id);
        $order->status = 3;
        $order->save();

        return $order;
    }

    public function hold(int $pos_receipt_id)
    {
        $order = $this->get($pos_receipt_id);
        $order->status = 2;
        $order->save();

        return $order;
    }

    public function done(int $pos_receipt_id)
    {
        $order = $this->get($pos_receipt_id);
        $order->status = 1;
        $order->save();

        return $order;
    }

    public function customerUpdate(array $cart)
    {
        $order = $this->get($cart['order_id']);
        if (isset($cart['customer_id'])) {
            $order['customer_id'] = $cart['customer_id'];
        }
        if (isset($cart['customer_type_slug'])) {
            $order['customer_type'] = $cart['customer_type_slug'];
        }
        $order->save();
    }
}
