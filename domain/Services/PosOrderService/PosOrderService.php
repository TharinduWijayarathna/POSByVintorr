<?php

namespace domain\Services\PosOrderService;

use App\Models\BillPayment;
use App\Models\BusinessDetail;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Product;
use App\Models\ProductTax;
use App\Models\StockLog;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use Carbon\Carbon;
use DateTime;
use domain\Facades\StockFacade\StockFacade;
use domain\Facades\StockLogFacade\StockLogFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * PosOrderService
 * php version 8
 *
 * @category Service
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class PosOrderService
{
    private $product;

    private $pos_order;

    private $pos_order_item;

    private $bill_payment;

    private $transaction;

    private $customer;

    private $transaction_balance;

    private $currency;

    private $product_tax;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->product = new Product;
        $this->pos_order = new PosOrder;
        $this->pos_order_item = new PosOrderItem;
        $this->bill_payment = new BillPayment;
        $this->transaction = new Transaction;
        $this->customer = new Customer;
        $this->transaction_balance = new TransactionBalance;
        $this->currency = new Currency;
        $this->product_tax = new ProductTax;
    }

    /**
     * All
     * retrieve all the data from PosOrder model
     *
     * @return void
     */
    public function all()
    {
        return $this->pos_order->all();
    }

    public function create()
    {
        $data['created_by'] = Auth::id();
        $count = $this->pos_order->where('code', 'like', 'C%')->count();

        $code = 'C'.sprintf('%05d', $count + 1);
        $check = $this->pos_order->getCode($code);

        while ($check) {
            $count++;
            $code = 'C'.sprintf('%05d', $count);
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
        $order = $this->pos_order->getActiveOrder();
        if ($order) {
            return $order;
        } else {
            return $this->create();
        }
    }

    public function get(int $pos_order_id)
    {
        return $this->pos_order->find($pos_order_id);
    }

    public function selectProduct($product_data, $order_data)
    {
        $order_item = $this->pos_order_item->where('product_id', $product_data['id'])->where('order_id', $order_data['id'])->first();
        if (! empty($order_item)) {
            if ($order_item->quantity < 10000) {
                $order_item->quantity += 1;
                $order_item->sub_total = $order_item->quantity * $order_item['unit_price'];
                $order_item->total = $order_item->quantity * $order_item['unit_price'];
                $order_item->total_tax = $this->calculateTotalTax($order_item->quantity, $order_item->unit_price, $order_item->product_id);
                $order_item->save();
            } else {
                $errorMessage = 'Quantity cannot exceed 10,000';

                return response()->json([
                    'errors' => [
                        'quantity' => [$errorMessage],
                    ],
                    'quantity' => [$errorMessage],
                    0 => $errorMessage,
                    'message' => $errorMessage,
                ], 400);
            }
        } else {
            $data = [
                'product_id' => $product_data['id'],
                'product_name' => $product_data['name'],
                'description' => $product_data['description'],
                'order_id' => $order_data['id'],
                'quantity' => 1,
                'unit_price' => $product_data['selling'],
                'sub_total' => 1 * $product_data['selling'],
                'total' => 1 * $product_data['selling'],
                'total_tax' => $this->calculateTotalTax(1, $product_data['selling'], $product_data['id']),
            ];
            $this->pos_order_item->create($data);
        }
    }

    public function selectInvoiceProduct($product_data, $invoice_id, $request_data)
    {
        $invoice = $this->pos_order->where('id', $invoice_id)->first();
        if ($invoice->currency_id == null) {
            $business_details = BusinessDetail::first();
            $invoice->currency_id = $business_details->currency_id;
            $invoice->save();
        }
        if ($invoice->status == 0) {
            $invoice->status = 1;
            $invoice->save();
        }

        // $order_item = $this->pos_order_item->where('product_id', $product_data['id'])->where('order_id', $invoice_id)->first();
        // if (!empty($order_item)) {
        //     $order_item->unit_price = $request_data['selling_price'];
        //     $order_item->quantity = $order_item->quantity + $request_data['quantity'];
        //     $order_item->sub_total = $order_item->quantity * $request_data['selling_price'];
        //     $order_item->total = $order_item->quantity * $request_data['selling_price'];
        //     $order_item->save();
        // } else {

        // }
        $data = [
            'product_id' => $product_data['id'],
            'product_name' => $product_data['name'],
            'description' => $request_data['description'],
            'order_id' => $invoice_id,
            'quantity' => $request_data['quantity'],
            'unit_price' => $request_data['selling_price'],
            'sub_total' => $request_data['quantity'] * $request_data['selling_price'],
            'total' => 1 * $request_data['quantity'] * $request_data['selling_price'],
            'total_tax' => $this->calculateTotalTax($request_data['quantity'], $request_data['selling_price'], $product_data['id']),
        ];
        $this->pos_order_item->create($data);

        $product = $this->product->find($product_data['id']);
        if ($product->product_type == $this->product::PRODUCT_TYPE['stockable']) {
            $product->stock_quantity = $product->stock_quantity - $request_data['quantity'];
            $product->save();

            // Stock Log
            $today = Carbon::now()->format('Y-m-d H:i:s');
            $stock_log_data['product_id'] = $product->id;
            $stock_log_data['product_name'] = $product_data['name'];
            $stock_log_data['reference_id'] = $invoice->id;
            $stock_log_data['reference'] = $invoice->code;
            $stock_log_data['quantity'] = $request_data['quantity'] ?? 0;
            $stock_log_data['balance'] = $product->stock_quantity ?? 0;
            $stock_log_data['cost'] = $product->cost ?? 0;
            $stock_log_data['selling_price'] = $request_data['selling_price'] ?? 0;
            $stock_log_data['reason'] = 'Added to invoice';
            $stock_log_data['type'] = StockLog::TYPE['minus'];
            $user = Auth::user();
            $stock_log_data['created_by'] = $user->id;
            $stock_log_data['created_user_name'] = $user->name;
            $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_out'];
            $stock_log_data['date'] = $today;
            StockLogFacade::store($stock_log_data);
        }
    }

    /**
     * calculateTotalTax
     *
     * @param  mixed  $quantity
     * @param  mixed  $unit_price
     * @param  mixed  $product_id
     * @return void
     */
    public function calculateTotalTax($quantity, $unit_price, $product_id)
    {
        $taxes = $this->product_tax->where('product_id', $product_id)->get();
        $total_tax = 0;

        foreach ($taxes as $tax) {
            $tax_amount = ($unit_price * $quantity * ($tax->tax_rate / 100));
            $total_tax += $tax_amount;
        }

        return $total_tax;
    }

    public function getOrderProduct($order_data)
    {
        return $this->pos_order_item->getAll($order_data['id']);
    }

    public function getInvoiceProduct($invoice_id)
    {
        return $this->pos_order_item->getAll($invoice_id);
    }

    public function clearOrder($order_id)
    {
        $this->pos_order_item->where('order_id', $order_id)->delete();
        $order = $this->pos_order->where('id', $order_id)->first();
        if ($order) {
            $order->discount = 0;
            $order->discount_type = null;
            $order->total_tax = 0;

            $order->save();
        }
    }

    /**
     * getTotals
     *
     * @param  mixed  $order_id
     * @return void
     */
    public function getTotals($order_id)
    {
        $this->recalculateTaxes($order_id);

        $subTotal = $this->pos_order_item->subTotal($order_id);

        return $this->pos_order->updateTotals($order_id, $subTotal);
    }

    /**
     * recalculateTaxes
     *
     * @param  mixed  $order_id
     * @return void
     */
    public function recalculateTaxes($order_id)
    {
        $tax_total = $this->pos_order_item->totalTax($order_id);

        return $this->pos_order->updateTaxes($order_id, $tax_total);
    }

    public function checkInvoiceStatus($order_id)
    {
        $order = $this->pos_order->where('id', $order_id)->withTrashed()->first();
        $order_item_count = $this->pos_order_item->where('order_id', $order_id)->count();
        if ($order->total - $order->customer_paid <= 0) {
            $order->balance = $order->customer_paid - $order->total;
            if ($order_item_count >= 1) {
                $order->credit_status = 1;
            }
            $order->save();
        }
    }

    public function decreaseQty($product_data, int $order_id)
    {
        $order = $this->pos_order_item->where('product_id', $product_data['id'])->where('order_id', $order_id)->first();
        if ($order) {
            if ($order->quantity > 1) {
                $order->quantity -= 1;
                $order->sub_total = $order->quantity * $order['unit_price'];
                $order->total = $order->quantity * $order['unit_price'];
                $order->total_tax = $this->calculateTotalTax($order->quantity, $order['unit_price'], $order->product_id);
                $order->save();
            } else {
                $this->pos_order_item->where('product_id', $product_data['id'])->where('order_id', $order_id)->delete();
            }
        }
    }

    public function increaseQty($product_data, int $order_id)
    {
        $order = $this->pos_order_item->where('product_id', $product_data['id'])->where('order_id', $order_id)->first();
        if ($order) {
            if ($order->quantity < 10000) {
                $order->quantity += 1;
                $order->sub_total = $order->quantity * $order['unit_price'];
                $order->total = $order->quantity * $order['unit_price'];
                $order->total_tax = $this->calculateTotalTax($order->quantity, $order['unit_price'], $order->product_id);
                $order->save();
            } else {
                $errorMessage = 'Quantity cannot exceed 10,000';

                return response()->json([
                    'errors' => [
                        'quantity' => [$errorMessage],
                    ],
                    'quantity' => [$errorMessage],
                    0 => $errorMessage,
                    'message' => $errorMessage,
                ], 400);
            }
        }
    }

    public function updateQty($data, $order_item_id, int $order_id)
    {
        $order = $this->pos_order->where('id', $order_id)->first();
        $order_item = $this->pos_order_item->find($order_item_id);
        if ($order_item) {

            if ($order->type == 1) {
                // Update stock
                $product = $this->product->withTrashed()->find($order_item['product_id']);
                if ($product->product_type == $this->product::PRODUCT_TYPE['stockable']) {
                    $product->stock_quantity = $product->stock_quantity - ($data['quantity'] - $order_item->quantity);
                    $product->save();

                    // Stock Log
                    $today = Carbon::now()->format('Y-m-d H:i:s');
                    $stock_log_data['product_id'] = $product->id;
                    if (isset($data['product_name'])) {
                        $stock_log_data['product_name'] = $data['product_name'];
                    } else {
                        $stock_log_data['product_name'] = $product->name;
                    }
                    $stock_log_data['reference_id'] = $order->id;
                    $stock_log_data['reference'] = $order->code;
                    $quantity = $data['quantity'] - $order_item->quantity ?? 0;
                    $stock_log_data['balance'] = $product->stock_quantity ?? 0;
                    $stock_log_data['quantity'] = abs($quantity);
                    $stock_log_data['cost'] = $product->cost ?? 0;
                    $stock_log_data['selling_price'] = $product->selling ?? 0;
                    if ($quantity < 0) {
                        $stock_log_data['reason'] = 'Edited the qty in invoice';
                        $stock_log_data['type'] = StockLog::TYPE['plus'];
                        $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
                    } else {
                        $stock_log_data['reason'] = 'Edited the qty in invoice';
                        $stock_log_data['type'] = StockLog::TYPE['minus'];
                        $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_out'];
                    }
                    $user = Auth::user();
                    $stock_log_data['created_by'] = $user->id;
                    $stock_log_data['created_user_name'] = $user->name;
                    $stock_log_data['date'] = $today;
                    StockLogFacade::store($stock_log_data);
                }
            }

            if (isset($data['product_name'])) {
                $order_item->product_name = $data['product_name'];
            }
            $order_item->description = $data['description'];
            $order_item->quantity = $data['quantity'];
            $order_item->unit_price = $data['unit_price'];
            $order_item->sub_total = $order_item->quantity * $order_item->unit_price;
            $order_item->total = $order_item->quantity * $order_item->unit_price;
            $order_item->total_tax = $this->calculateTotalTax($order_item->quantity, $order_item->unit_price, $order_item->product_id);
            $order_item->save();
        }
    }

    public function changeUnitPrice($unit_price, $product_data, int $order_id)
    {
        $order = $this->pos_order_item->where('product_id', $product_data['id'])->where('order_id', $order_id)->first();
        if ($order) {
            $order->unit_price = $unit_price;
            $order->sub_total = $unit_price * $order['quantity'];
            $order->total = $unit_price * $order['quantity'];
            $order->total_tax = $this->calculateTotalTax($order->quantity, $unit_price, $order->product_id);
            $order->save();
        }
    }

    public function removeItem($pos_order_item_id, int $order_id)
    {
        $order = $this->pos_order->where('id', $order_id)->first();
        $order_item = $this->pos_order_item->find($pos_order_item_id);

        if ($order->type == 1) {
            $product = $this->product->withTrashed()->find($order_item['product_id']);
            if ($product->product_type == $this->product::PRODUCT_TYPE['stockable']) {
                $product->stock_quantity = $product->stock_quantity + $order_item['quantity'];
                $product->save();

                // Stock Log
                $today = Carbon::now()->format('Y-m-d H:i:s');
                $stock_log_data['product_id'] = $product->id;
                $stock_log_data['product_name'] = $order_item->product_name;
                $stock_log_data['reference_id'] = $order->id;
                $stock_log_data['reference'] = $order->code;
                $stock_log_data['quantity'] = $order_item['quantity'] ?? 0;
                $stock_log_data['balance'] = $product->stock_quantity ?? 0;
                $stock_log_data['cost'] = $product->cost ?? 0;
                $stock_log_data['selling_price'] = $product->selling ?? 0;
                $stock_log_data['reason'] = 'Removed from invoice';
                $stock_log_data['type'] = StockLog::TYPE['plus'];
                $user = Auth::user();
                $stock_log_data['created_by'] = $user->id;
                $stock_log_data['created_user_name'] = $user->name;
                $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
                $stock_log_data['date'] = $today;
                StockLogFacade::store($stock_log_data);
            }
        }

        $order_item->delete();
    }

    public function customerUpdate(int $customer_id, int $order_id)
    {
        $order = $this->get($order_id);
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
        $order->save();

        return $order;
    }

    public function removeCustomerId($order_id)
    {
        // dd($order_id);
        $order = $this->pos_order->where('id', $order_id)->first();
        if ($order) {
            $order->customer_id = null;
            $order->customer_type = null;
            $order->customer_name = null;
            $order->customer_address = null;
            $order->customer_email = null;
            $order->customer_mobile = null;
            $order->save();
        }

        return $order;
    }

    public function holdCart(int $order_id)
    {
        $order = $this->pos_order->where('id', $order_id)->first();
        if ($order) {
            $order->status = 2;

            $today = \Carbon\Carbon::now()->toDateString();
            $order->date = $today;

            $business_details = BusinessDetail::first();
            $currency_id = $business_details->currency_id;
            $order->currency_id = $currency_id;

            return $order->save();
        }
    }

    public function paymentDone($request)
    {
        if (isset($request['date'])) {
            $date = $request['date'];
            $dateObject = new DateTime($date);
            $today = $dateObject->format('Y-m-d');
        } else {
            $today = Carbon::now()->toDateString();
        }

        if (isset($request['currency_id'])) {
            $currency_id = $request['currency_id'];
        } else {
            $business_details = BusinessDetail::first();
            $currency_id = $business_details->currency_id;
        }

        $order = $this->pos_order->where('id', $request['order_id'])->first();
        $customer = $this->customer->find($request['customer_id']);

        if (($order->total - $order->customer_paid) >= $request['paid_amount']) {
            $request['paid_amount'] = $request['paid_amount'];

            // $customer->customer_outstanding = $customer->customer_outstanding + ($order->total - $request['paid_amount']);

            $request['accepted_amount'] = $request['paid_amount'];
            $order->customer_paid = $order->customer_paid + $request['paid_amount'];
            $order->balance = 0;
        } else {
            $request['accepted_amount'] = $order->total - $order->customer_paid;

            // if (isset($customer->customer_outstanding)) {
            //     $customer->customer_outstanding = $customer->customer_outstanding - $request['accepted_amount'];
            // }
            $request['paid_amount'] = $request['paid_amount'];
            $request['change'] = $request['paid_amount'] - $request['accepted_amount'];
            $request['balance'] = 0;
            $order->customer_paid = $order->customer_paid + $request['accepted_amount'];
            $order->balance = $request['change'];
        }

        $order->status = 1;
        $order->currency_id = $currency_id;
        if (isset($request['date'])) {
            $date = date('Y-m-d', strtotime($request['date']));
            $order->date = $date;
        } else {
            $order->date = $today;
        }
        if (isset($request['due_date'])) {
            $due_day = date('Y-m-d', strtotime($request['due_date']));
            $order->due_date = $due_day;
        }
        $order->save();
        $updated_order = $this->pos_order->where('id', $request['order_id'])->first();
        if ($updated_order->total == $updated_order->customer_paid) {
            $updated_order->status = 1;
            $updated_order->credit_status = 1;
            $updated_order->save();
        }

        // payment bill
        $request['created_by'] = Auth::id();
        $count = $this->bill_payment->count();

        $code = 'B'.sprintf('%05d', $count + 1);
        $check = $this->bill_payment->getCode($code);

        while ($check) {
            $count++;
            $code = 'B'.sprintf('%05d', $count);
            $check = $this->bill_payment->getCode($code);
        }

        $request['code'] = $code;
        $request['date'] = $today;

        $created_bill = $this->bill_payment->create($request);
        // end payment bill

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
        $transaction_data['type'] = 1;
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
        }
        $transaction_data['currency_id'] = $updated_order->currency_id;
        $transaction_data['amount'] = $updated_order->customer_paid;
        $transaction_data['description'] = 'POS Payment';
        $this->transaction->create($transaction_data);

        $transaction_balance = $this->transaction_balance->where('currency_id', $transaction_data['currency_id'])->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount + $updated_order->customer_paid;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($transaction_data['currency_id']);
            $balance_data['currency_id'] = $transaction_data['currency_id'];
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = $updated_order->customer_paid;
            $this->transaction_balance->create($balance_data);
        }

        // end transaction log

        StockFacade::productDecrease($request['order_id']);
    }

    public function discount(array $data)
    {
        $order = $this->pos_order->where('id', $data['orderId'])->first();
        if ($order) {
            if ($order->total - $order->customer_paid <= $data['discountAmount']) {
                $order->total = $order->total - $data['discountAmount'];
                $order->balance = $order->customer_paid - $order->total;
                $order->customer_paid = $order->total;
                $order->credit_status = 1;

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
                $transaction_data['type'] = 2;
                $transaction_data['date'] = Carbon::now();
                $transaction_data['reference_code'] = $order->code;
                $transaction_data['payment_code'] = $order->code;
                $transaction_data['client'] = $order->customer_name;
                $transaction_data['currency_id'] = $order->currency_id;
                $transaction_data['amount'] = $order->balance;
                $transaction_data['sign'] = 0;
                $transaction_data['description'] = 'Add Discount';
                $this->transaction->create($transaction_data);

                $transaction_balance = $this->transaction_balance->where('currency_id', $order->currency_id)->first();
                if ($transaction_balance) {
                    $transaction_balance->amount = $transaction_balance->amount - $order->balance;
                    $transaction_balance->save();
                } else {
                    $currency = $this->currency->find($transaction_data['currency_id']);
                    $balance_data['currency_id'] = $transaction_data['currency_id'];
                    $balance_data['code'] = $currency['code'];
                    $balance_data['amount'] = -$order->balance;
                    $this->transaction_balance->create($balance_data);
                }
            }
            if ($data['discountType'] == 1) {
                $order->discount = $data['discountAmount'];
                $order->discount_type = 0;
            }
            if ($data['discountType'] == 2) {
                $order->discount = $data['discountAmount'];
                $order->discount_type = 1;
            }
            $order->save();

            return $order;
        }
    }

    public function removeDiscount($order_id)
    {
        $order = $this->pos_order->where('id', $order_id)->first();
        if ($order) {
            $order->discount = 0;
            $order->discount_type = null;
            $order->save();
        }
    }

    public function reActive($order_id)
    {

        $previousOrder = $this->pos_order->where('created_by', Auth::user()->id)->where('status', 0)->where('type', 0)->where('code', 'not like', 'R%')->first();

        if ($previousOrder != null) {
            if ($previousOrder->sub_total > 0) {
                $previousOrder->status = 2;
                $previousOrder->save();
            } else {
                // dd($previousOrder->status);
                // $previousOrder->delete();
                $previousOrder->status = 3;
                $previousOrder->update();
            }
        }

        $order = $this->pos_order->where('id', $order_id)->first();
        if ($order) {
            if ($order->created_by == null) {
                $order->created_by = Auth::user()->id;
            }
            if ($order->created_by != Auth::user()->id) {
                $order->created_by = Auth::user()->id;
            }
            $order->status = 0;
            $order->save();

            return $order;
        }
    }

    public function editInvoice($invoice_id)
    {
        return $this->pos_order->where('id', $invoice_id)->first();
    }

    public function reActiveInvoice($order_id)
    {

        $previousOrder = $this->pos_order->where('created_by', Auth::user()->id)->where('status', 0)->where('type', 1)->where('code', 'not like', 'R%')->first();

        if ($previousOrder->sub_total > 0) {
            $previousOrder->status = 2;
            $previousOrder->save();
        } else {
            // dd($previousOrder->status);
            // $previousOrder->delete();
            $previousOrder->status = 3;
            $previousOrder->update();
        }

        $order = $this->pos_order->where('id', $order_id)->first();
        if ($order) {
            if ($order->created_by == null) {
                $order->created_by = Auth::user()->id;
            }
            if ($order->created_by != Auth::user()->id) {
                $order->created_by = Auth::user()->id;
            }
            $order->status = 0;
            $order->save();

            return $order;
        }
    }

    public function deleteOrder($order_id)
    {
        return $this->pos_order->find($order_id)->delete();
    }

    public function returnUpdate(int $order_id)
    {
        $orderItem = $this->get($order_id);
        if ($orderItem) {
            $orderItem->is_return = 1;
            $orderItem->save();
        }
    }

    public function getPopularProducts($pos_order_ids)
    {
        return $this->pos_order_item
            ->whereIn('pos_order_items.order_id', $pos_order_ids)
            ->select(
                'pos_order_items.product_id',
                DB::raw('SUM(pos_order_items.quantity) as total_quantity'),
                DB::raw('SUM(pos_order_items.total) as total_amount')
            )
            ->groupBy('pos_order_items.product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();
    }

    public function getPopularProductsInspector($pos_order_ids)
    {
        return $this->pos_order_item
            ->whereIn('pos_order_items.order_id', $pos_order_ids)
            ->join('products', 'pos_order_items.product_id', '=', 'products.id')
            ->where('products.visibility', $this->product::VISIBILITY['visible'])
            ->select(
                'pos_order_items.product_id',
                DB::raw('SUM(pos_order_items.quantity) as total_quantity'),
                DB::raw('SUM(pos_order_items.total) as total_amount')
            )
            ->groupBy('pos_order_items.product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();
    }
}
