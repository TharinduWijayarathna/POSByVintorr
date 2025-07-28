<?php

namespace domain\Services\ReturnService;

use App\Models\BusinessDetail;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use Illuminate\Support\Facades\Auth;

class ReturnService
{
    protected $pos_order;

    protected $pos_order_item;

    protected $product;

    protected $transaction;

    protected $customer;

    private $transaction_balance;

    private $currency;

    public function __construct()
    {
        $this->pos_order = new PosOrder;
        $this->pos_order_item = new PosOrderItem;
        $this->product = new Product;
        $this->transaction = new Transaction;
        $this->customer = new Customer;
        $this->transaction_balance = new TransactionBalance;
        $this->currency = new Currency;
    }

    public function create()
    {
        $data['created_by'] = Auth::id();
        $data['is_return'] = 1;
        $count = $this->pos_order->withTrashed()->where('code', 'like', 'R%')->count();

        $code = 'R'.sprintf('%05d', $count + 1);
        $check = $this->pos_order->getCode($code);

        while ($check) {
            $count++;
            $code = 'R'.sprintf('%05d', $count);
            $check = $this->pos_order->getCode($code);
        }

        $data['code'] = $code;

        return $this->pos_order->create($data);
    }

    /**
     * getOrCreate
     *
     * @return void
     */
    public function getOrCreate()
    {
        $order = $this->pos_order->getReturnOrder();
        if ($order) {
            return $order;
        } else {
            return $this->create();
        }
    }

    public function getOrder($order_code)
    {
        return $this->pos_order->where('created_by', Auth::user()->id)->where('code', $order_code)->first();
    }

    public function get($order_code)
    {
        $order = $this->pos_order->where('created_by', Auth::user()->id)->where('code', $order_code)->first();
        if ($order) {
            return $this->pos_order_item->where('order_id', $order->id)->get();
        }
    }

    public function addItems($data, $id)
    {
        $product = $this->product->find($data['product_id']);
        $data['product_id'] = $data['product_id'];
        $data['product_name'] = $product->name;
        $data['order_id'] = $id;
        $data['return_status'] = 1;
        if ($data['unit_price'] == null) {
            $data['unit_price'] = 0;
        } else {
            $data['unit_price'] = $data['unit_price'];
        }
        $data['sub_total'] = $data['quantity'] * $data['unit_price'];
        $data['total'] = $data['quantity'] * $data['unit_price'];
        $data['return_quantity'] = +$data['quantity'];
        $data['quantity'] = -$data['quantity'];
        $data['type'] = 0;

        return $this->pos_order_item->create($data);
    }

    public function getReturnProduct($order_data)
    {
        return $this->pos_order_item->getAll($order_data['id']);
    }

    public function deleteItem(int $id)
    {
        return $this->pos_order_item->find($id)->delete();
    }

    public function getTotals($order_id)
    {
        return $this->pos_order_item->where('order_id', $order_id)->get()->sum('total');
    }

    public function customerUpdate(int $customer_id, int $order_id)
    {
        $order = $this->pos_order->where('id', $order_id)->first();
        if (isset($customer_id)) {
            $order['customer_type'] = 'loyalty-customer';
            $order['customer_id'] = $customer_id;
        }
        $customer = $this->customer->find($customer_id);
        if (isset($customer->name)) {
            $order['customer_name'] = $customer->name;
        }
        if (isset($customer->address)) {
            $order['customer_address'] = $customer->address;
        }
        if (isset($customer->contact)) {
            $order['customer_mobile'] = $customer->contact;
        }
        if (isset($customer->email)) {
            $order['customer_email'] = $customer->email;
        }
        $order->save();

        return $order;
    }

    public function returnEditOrder($order_id)
    {
        return $this->pos_order->withTrashed()->find($order_id);
    }

    public function removeCustomerId($order_id)
    {
        $order = $this->pos_order->where('id', $order_id)->first();
        if ($order) {
            $order->customer_id = null;
            $order->customer_type = null;
            $order->save();
        }

        return $order;
    }

    public function returnDone($request)
    {
        $date = \Carbon\Carbon::now();
        $today = $date->toDateString();

        $order = $this->pos_order->where('id', $request['order_id'])->first();

        if (isset($request['currency_id'])) {
            $currency_id = $request['currency_id'];
        } else {
            $business_details = BusinessDetail::first();
            $currency_id = $business_details->currency_id;
        }

        $order->status = 4;
        $order->total = -($request['order_total']);
        $order->sub_total = -($request['order_total']);
        $order->remark = $request['remark'];
        $order->date = $today;
        $order->created_by = Auth::id();
        $order->currency_id = $currency_id;

        $order->save();

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
        $transaction_data['type'] = 4;
        $transaction_data['date'] = $today;
        $transaction_data['reference_code'] = $order->code;
        $transaction_data['payment_code'] = $order->code;
        if ($order->customer_id != null) {
            $customer = $this->customer->withTrashed()->find($order->customer_id);
            $transaction_data['customer_id'] = $order->customer_id;
            if ($customer) {
                $transaction_data['client'] = $customer->name;
            } else {
                $transaction_data['client'] = 'Customer not available';
            }
        }
        $transaction_data['currency_id'] = $order->currency_id;
        $transaction_data['amount'] = abs($order->total);
        $transaction_data['remark'] = $order->remark;
        $transaction_data['description'] = 'Return';
        $transaction_data['sign'] = 0;
        $this->transaction->create($transaction_data);

        $transaction_balance = $this->transaction_balance->where('currency_id', $transaction_data['currency_id'])->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount - abs($order->total);
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($transaction_data['currency_id']);
            $balance_data['currency_id'] = $transaction_data['currency_id'];
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = $order->total;
            $this->transaction_balance->create($balance_data);
        }
        // end transaction log
    }

    public function returnUpdate($request)
    {
        $order = $this->pos_order->where('id', $request['order_id'])->first();

        if (isset($request['currency_id'])) {
            $currency_id = $request['currency_id'];
        } else {
            $business_details = BusinessDetail::first();
            $currency_id = $business_details->currency_id;
        }

        // transaction log
        $created_transaction = $this->transaction->where('reference_code', $order->code)->where('payment_code', $order->code)->first();
        if ($created_transaction) {

            $created_transaction->remark = $request['remark'];
            if (isset($order['customer_id'])) {
                $customer = $this->customer->withTrashed()->find($order['customer_id']);
                $created_transaction->customer_id = $order['customer_id'];
                if ($customer) {
                    $created_transaction->client = $customer->name;
                } else {
                    $created_transaction->client = 'Customer not available';
                }

            } else {
                $created_transaction->customer_id = null;
                $created_transaction->client = null;
            }

            $transaction_balance = $this->transaction_balance->where('currency_id', $request['currency_id'])->first();
            if ($transaction_balance) {
                if ($order->currency_id == $request['currency_id']) {
                    $transaction_balance->amount = $transaction_balance->amount - ($request['order_total'] - $created_transaction->amount);
                    $transaction_balance->save();
                } else {
                    $previous_transaction_balance = $this->transaction_balance->where('currency_id', $order->currency_id)->first();
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount + abs($order->sub_total);
                    $previous_transaction_balance->save();
                    $transaction_balance->amount = $transaction_balance->amount - $request['order_total'];
                    $transaction_balance->save();
                }
            } else {
                $previous_transaction_balance = $this->transaction_balance->where('currency_id', $order->currency_id)->first();
                if ($order->currency_id != $request['currency_id']) {
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount + abs($order->sub_total);
                    $previous_transaction_balance->save();
                }
                $currency = $this->currency->find($request['currency_id']);
                $balance_data['currency_id'] = $request['currency_id'];
                $balance_data['code'] = $currency['code'];
                $balance_data['amount'] = -$request['order_total'];
                $this->transaction_balance->create($balance_data);
            }

            $created_transaction->currency_id = $request['currency_id'];

            $created_transaction->amount = $request['order_total'];
            $created_transaction->save();
        }

        $order->total = -($request['order_total']);
        $order->sub_total = -($request['order_total']);
        $order->remark = $request['remark'];
        $order->currency_id = $currency_id;

        return $order->save();
        // end transaction log
    }

    public function delete(int $order_id)
    {
        // transaction log
        $order = $this->pos_order->find($order_id);
        $transaction = $this->transaction->where('reference_code', $order->code)->first();

        $transaction_balance = $this->transaction_balance->where('currency_id', $order->currency_id)->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount + abs($order->sub_total);
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($order->currency_id);
            $balance_data['currency_id'] = $order->currency_id;
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = abs($order->sub_total);
            $this->transaction_balance->create($balance_data);
        }

        $transaction->delete();

        return $order->delete();
    }

    public function restoreReturn(int $order_id)
    {
        $deleted_return = $this->pos_order->withTrashed()->find($order_id);
        $deleted_return->deleted_at = null;
        $deleted_return->save();

        // transaction log
        $order = $this->pos_order->find($order_id);
        $deleted_transaction = $this->transaction->where('reference_code', $order->code)->withTrashed()->first();
        $deleted_transaction->deleted_at = null;
        $deleted_transaction->save();

        $transaction_balance = $this->transaction_balance->where('currency_id', $order->currency_id)->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount - abs($order->sub_total);
            $transaction_balance->save();
        }

        return $order;
    }

    /**
     * ResetPosOrderItem
     * remove pos order items belongs to that order_id
     *
     *
     * @return void
     */
    public function removePosOrderItems($order_id)
    {

        return $this->pos_order_item->where('order_id', $order_id)->delete();

    }

    /**
     * ResetReturnDraft
     * reset draft pos order into default values
     *
     *
     * @return void
     */
    public function resetReturnDraft($order_id, $user_id)
    {
        $order = $this->pos_order->find($order_id);

        return $order->update([
            'id' => $order->id,
            'created_by' => $user_id,
            'customer_type' => null,
            'customer_id' => null,
            'code' => $order->code,
            'status' => 0,
            'sub_total' => 0,
            'total' => 0,
            'discount' => 0,
            'discount_type' => null,
            'sales_person_id' => null,
            'customer_paid' => 0,
            'balance' => 0,
            'is_return' => 1,
            'remark' => null,
            'ref_no' => null,
            'credit_status' => 0,
            'discount_remark' => null,
            'payment_type' => null,
            'total_tax' => 0,
            'deleted_at' => null,
            'created_at' => null,
            'updated_at' => null,
            'currency_id' => null,
            'type' => 0,
            'date' => null,
            'due_date' => null,
            'note' => null,
            'customer_name' => null,
            'customer_address' => null,
            'customer_email' => null,
            'customer_mobile' => null,
            'invoice_link' => null,
        ]);
    }
}
