<?php

namespace domain\Services\InvoiceService;

use App\Jobs\SendCustomerInvoiceMailJob\SendCustomerInvoiceMailJob;
use App\Models\BillPayment;
use App\Models\BusinessDetail;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\InvoiceFooterParameter;
use App\Models\InvoiceItemFooterParameter;
use App\Models\InvoiceItemParameter;
use App\Models\InvoiceParameter;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Product;
use App\Models\StockLog;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use Carbon\Carbon;
use domain\Facades\BillPaymentFacade\BillPaymentFacade;
use domain\Facades\InvoiceFacade\InvoiceFacade;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\PosOrderItemFacade\PosOrderItemFacade;
use domain\Facades\StockLogFacade\StockLogFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class InvoiceService
{
    private $product;

    private $pos_order;

    private $pos_order_item;

    private $customer;

    private $bill_payment;

    private $transaction;

    private $invoice_parameter;

    private $invoice_item_parameter;

    private $invoice_footer_parameter;

    private $invoice_item_footer_parameter;

    private $transaction_balance;

    private $currency;

    private $business_details;

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
        $this->customer = new Customer;
        $this->bill_payment = new BillPayment;
        $this->transaction = new Transaction;
        $this->invoice_parameter = new InvoiceParameter;
        $this->invoice_item_parameter = new InvoiceItemParameter;
        $this->invoice_footer_parameter = new InvoiceFooterParameter;
        $this->invoice_item_footer_parameter = new InvoiceItemFooterParameter;
        $this->transaction_balance = new TransactionBalance;
        $this->currency = new Currency;
        $this->business_details = new BusinessDetail;
    }

    public function get(int $invoice_id)
    {
        return $this->pos_order->find($invoice_id);
    }

    public function create()
    {
        $business_detail = $this->business_details->first();
        $data['created_by'] = Auth::id();

        $last_record = $this->pos_order->where('type', '1')->withTrashed()->latest()->first();
        if ($last_record) {

            $old_code = $last_record['code'];
            preg_match('/(\d+)$/', $old_code, $matches);
            $numericPart = $matches[1];
            // Increment the numeric part by 1
            $incrementedNumericPart = intval($numericPart) + 1;
            // Replace the old numeric part with the incremented one
            $code = preg_replace('/\d+$/', str_pad($incrementedNumericPart, strlen($numericPart), '0', STR_PAD_LEFT), $old_code);

            $check = $this->pos_order->where('type', '1')->where('code', $code)->withTrashed()->first();
            while ($check) {
                $incrementedNumericPart++;
                $code = preg_replace('/\d+$/', str_pad($incrementedNumericPart, strlen($numericPart), '0', STR_PAD_LEFT), $old_code);
                $check = $this->pos_order->where('type', '1')->where('code', $code)->withTrashed()->first();
            }
        } else {

            $count = $this->pos_order->where('code', 'like', 'INV%')->withTrashed()->count();

            $code = 'INV'.sprintf('%05d', $count + 1);
            $check = $this->pos_order->getCode($code);

            while ($check) {
                $count++;
                $code = 'INV'.sprintf('%05d', $count);
                $check = $this->pos_order->getCode($code);
            }
        }

        $data['code'] = $code;
        $data['type'] = 1;
        $data['currency_id'] = $business_detail->currency_id;
        $data['date'] = Carbon::today();
        $createdPosOrder = $this->pos_order->create($data);
        $createdPosOrderId = $createdPosOrder->id;

        $request['created_by'] = Auth::id();
        $count = $this->bill_payment->count();

        $code = 'B'.sprintf('%05d', $count + 1);
        $check = $this->bill_payment->getCode($code);

        while ($check) {
            $count++;
            $code = 'B'.sprintf('%05d', $count);
            $check = $this->bill_payment->getCode($code);
        }

        $request['order_id'] = $createdPosOrderId;
        $request['code'] = $code;
        $request['date'] = \Carbon\Carbon::now()->toDateString();

        $this->bill_payment->create($request);

        return $createdPosOrder;
    }

    /**
     * getOrCreate
     *
     * @return void
     */
    public function getOrCreate()
    {
        $order = $this->pos_order->getInvoiceOrder();
        if ($order) {
            $date = Carbon::today();
            $order->update([
                'date' => $date,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            return $order;
        } else {
            return $this->create();
        }
    }

    /**
     * getOrCreateNew
     *
     * @return void
     */
    public function getOrCreateNew()
    {
        $order = $this->pos_order->getInvoiceOrderNew();
        if ($order) {
            $date = Carbon::today();

            $business_detail = $this->business_details->first();
            $this->pos_order_item->where('order_id', $order->id)->delete();
            $this->bill_payment->where('order_id', $order->id)->where('order_total', '>', 0)->delete();

            $this->invoice_item_parameter->where('invoice_id', $order->id)->update(['description' => null]);

            $order->update([
                'date' => $date,
                'due_date' => null,
                'created_at' => $date,
                'updated_at' => $date,
                'customer_id' => null,
                'customer_name' => null,
                'customer_address' => null,
                'customer_email' => null,
                'customer_mobile' => null,
                'sub_total' => 0,
                'total' => 0,
                'discount' => 0,
                'discount_type' => null,
                'customer_paid' => 0,
                'balance' => 0,
                'remark' => null,
                'ref_no' => null,
                'credit_status' => 0,
                'currency_id' => $business_detail->currency_id,
                'note' => null,
            ]);

            return $order;
        } else {
            return $this->create();
        }
    }

    public function storeCustomer(int $invoice_id, int $pos_customer_id)
    {
        $invoice = $this->pos_order->find($invoice_id);
        $customer = $this->customer->find($pos_customer_id);
        if (! $customer) {
            // Handle the case where customer is not found
            $customer = (object) [
                'name' => 'Walking Customer',
                'address' => '',
                'email' => '',
                'contact' => '',
            ];
        }
        if ($invoice->customer_id != $pos_customer_id || $invoice->customer_id == null) {
            $invoice->customer_id = $pos_customer_id;
            $invoice->customer_name = $customer->name;
            $invoice->customer_address = $customer->address;
            $invoice->customer_email = $customer->email;
            $invoice->customer_mobile = $customer->contact;

            return $invoice->save();
        }
    }

    public function storeDate(int $invoice_id, $data)
    {
        $dateString = $data['date'];
        $date = Carbon::parse($dateString);
        $invoice = $this->pos_order->find($invoice_id);
        $invoice->date = $date;

        return $invoice->save();
    }

    public function storeDueDate(int $invoice_id, $data)
    {
        $dateString = $data['date'];
        $date = Carbon::parse($dateString);
        $invoice = $this->pos_order->find($invoice_id);
        $invoice->due_date = $date;

        // dd($invoice);
        return $invoice->save();
    }

    public function storeNote(int $invoice_id, $data)
    {
        $invoice = $this->pos_order->find($invoice_id);
        $invoice->note = $data['note'];

        return $invoice->save();
    }

    public function storeCode($data, int $invoice_id)
    {
        $invoice = $this->pos_order->find($invoice_id);
        $invoice->code = $data['code'];

        return $invoice->save();
    }

    public function storeCurrency(int $invoice_id, int $currency_id)
    {
        $invoice = $this->pos_order->find($invoice_id);
        $invoice->currency_id = $currency_id;

        return $invoice->save();
    }

    public function storeInvoiceRef($data, int $invoice_id)
    {
        $pos_order = $this->pos_order->find($invoice_id);
        $pos_order->ref_no = $data['ref'];

        return $pos_order->save();
    }

    public function getLoyaltyCustomer(int $invoice_id)
    {
        $invoice = $this->pos_order->withTrashed()->find($invoice_id);

        return $this->customer->find($invoice->customer_id);
    }

    public function changeInvoiceCustomer($data, int $invoice_id)
    {
        $pos_order = $this->pos_order->find($invoice_id);
        $pos_order->customer_mobile = $data['customer_mobile'];
        $pos_order->customer_email = $data['customer_email'];
        $pos_order->customer_address = $data['customer_address'];

        return $pos_order->save();
    }

    public function getRelatedInvoice(int $invoice_id)
    {
        return $this->pos_order->withTrashed()->find($invoice_id);
    }

    // Header Parameter
    public function storeParameter(int $invoice_id, $data)
    {
        $ip_count = $this->invoice_item_parameter->where('invoice_id', $invoice_id)->count();
        $created_parameter = $this->invoice_parameter->create($data);
        $this->invoice_item_parameter->create([
            'invoice_id' => $invoice_id,
            'name' => $created_parameter->title,
            'parameter_id' => $created_parameter->id,
            'order' => $ip_count + 1,
        ]);

    }

    public function updateParameter($data)
    {
        $invoice_item_parameter = $this->invoice_item_parameter->find($data['id']);
        $invoice_item_parameter->name = $data['title'];
        $invoice_item_parameter->save();

        $invoice_parameter = $this->invoice_parameter->find($invoice_item_parameter->parameter_id);
        if ($invoice_parameter) {
            $invoice_parameter->title = $data['title'];
            $invoice_parameter->save();
        }
    }

    public function deleteParameter($id)
    {
        $invoice_item_parameter = $this->invoice_item_parameter->find($id);
        $invoice_parameter = $this->invoice_parameter->find($invoice_item_parameter->parameter_id);
        if ($invoice_parameter) {
            $invoice_parameter->delete();
        }

        return $invoice_item_parameter->delete();
    }

    public function getParameters(int $invoice_id)
    {
        $invoice_parameters = $this->invoice_parameter->get();
        $invoice_item_parameters = $this->invoice_item_parameter->where('invoice_id', $invoice_id)->get();
        if ($invoice_item_parameters->isEmpty()) {

            if ($invoice_parameters->count() > 0) {
                foreach ($invoice_parameters as $invoice_parameter) {
                    $ip_count = $this->invoice_item_parameter->where('invoice_id', $invoice_id)->count();
                    $this->invoice_item_parameter->create([
                        'invoice_id' => $invoice_id,
                        'name' => $invoice_parameter->title,
                        'parameter_id' => $invoice_parameter->id,
                        'order' => $ip_count + 1,
                    ]);
                }

                return $this->invoice_item_parameter->where('invoice_id', $invoice_id)->orderBy('order', 'asc')->get();
            }
        } else {

            return $invoice_item_parameters;
        }
    }

    public function setDescription($data)
    {
        $invoice_item_parameters = $this->invoice_item_parameter->find($data['id']);
        $invoice_item_parameters->description = $data['description'];

        return $invoice_item_parameters->save();
    }

    public function editParameter($id)
    {
        return $this->invoice_item_parameter->find($id);
    }
    // End Header Parameter

    // Footer Parameter
    public function storeFooterParameter(int $invoice_id, $data)
    {
        $ip_count = $this->invoice_item_footer_parameter->where('invoice_id', $invoice_id)->count();
        $created_footer_parameter = $this->invoice_footer_parameter->create($data);
        $this->invoice_item_footer_parameter->create([
            'invoice_id' => $invoice_id,
            'name' => $created_footer_parameter->title,
            'parameter_id' => $created_footer_parameter->id,
            'order' => $ip_count + 1,
        ]);

    }

    public function updateFooterParameter($data)
    {
        $invoice_item_footer_parameter = $this->invoice_item_footer_parameter->find($data['id']);
        $invoice_item_footer_parameter->name = $data['title'];
        $invoice_item_footer_parameter->save();

        $invoice_footer_parameter = $this->invoice_footer_parameter->find($invoice_item_footer_parameter->parameter_id);
        if ($invoice_footer_parameter) {
            $invoice_footer_parameter->title = $data['title'];
            $invoice_footer_parameter->save();
        }
    }

    public function deleteFooterParameter($id)
    {
        $invoice_item_footer_parameter = $this->invoice_item_footer_parameter->find($id);
        $invoice_footer_parameter = $this->invoice_footer_parameter->find($invoice_item_footer_parameter->parameter_id);
        if ($invoice_footer_parameter) {
            $invoice_footer_parameter->delete();
        }

        return $invoice_item_footer_parameter->delete();
    }

    public function getFooterParameters(int $invoice_id)
    {
        $invoice_footer_parameters = $this->invoice_footer_parameter->get();
        $invoice_item_footer_parameters = $this->invoice_item_footer_parameter->where('invoice_id', $invoice_id)->get();
        if ($invoice_item_footer_parameters->isEmpty()) {

            if ($invoice_footer_parameters->count() > 0) {
                foreach ($invoice_footer_parameters as $invoice_footer_parameter) {
                    $ip_count = $this->invoice_item_footer_parameter->where('invoice_id', $invoice_id)->count();
                    $this->invoice_item_footer_parameter->create([
                        'invoice_id' => $invoice_id,
                        'name' => $invoice_footer_parameter->title,
                        'description' => $invoice_footer_parameter->description,
                        'parameter_id' => $invoice_footer_parameter->id,
                        'order' => $ip_count + 1,
                    ]);
                }

                return $this->invoice_item_footer_parameter->where('invoice_id', $invoice_id)->orderBy('order', 'asc')->get();
            }
        } else {

            return $invoice_item_footer_parameters;
        }
    }

    public function setFooterDescription($data)
    {
        $invoice_item_footer_parameters = $this->invoice_item_footer_parameter->find($data['id']);
        $invoice_item_footer_parameters->description = $data['description'];
        $invoice_item_footer_parameters->save();

        $invoice_footer_parameter = $this->invoice_footer_parameter->find($invoice_item_footer_parameters->parameter_id);
        if ($invoice_footer_parameter) {
            $invoice_footer_parameter->description = $data['description'];
            $invoice_footer_parameter->save();
        }

    }

    public function editFooterParameter($id)
    {
        return $this->invoice_item_footer_parameter->find($id);
    }
    // End Footer Parameter

    public function parametersGetForPrint($invoice_id)
    {
        return $this->invoice_item_parameter->where('invoice_id', $invoice_id)->where('description', '!=', 'null')->get();
    }

    public function footerParametersGetForPrint($invoice_id)
    {
        return $this->invoice_item_footer_parameter->where('invoice_id', $invoice_id)->where('description', '!=', 'null')->get();
    }

    public function getInvoice($invoice_id)
    {
        return $this->pos_order->withTrashed()->find($invoice_id);
    }

    public function getForDelete(int $invoice_id)
    {
        return $this->pos_order->find($invoice_id);
    }

    public function delete(int $invoice_id)
    {
        $invoice = $this->pos_order->find($invoice_id);

        $transaction_data['code'] = $this->generateNewTRCode('TR');
        $transaction_data['type'] = 2;
        $transaction_data['date'] = Carbon::now();
        $transaction_data['reference_code'] = $invoice->code;
        $transaction_data['payment_code'] = $invoice->code;
        $transaction_data['client'] = $invoice->customer_name;
        $transaction_data['currency_id'] = $invoice->currency_id;
        $transaction_data['amount'] = $invoice->customer_paid;
        $transaction_data['sign'] = 0;
        $transaction_data['description'] = 'Invoice Delete';
        $this->transaction->create($transaction_data);

        $transaction_balance = $this->transaction_balance->where('currency_id', $invoice->currency_id)->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount - $invoice->customer_paid;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($transaction_data['currency_id']);
            $balance_data['currency_id'] = $transaction_data['currency_id'];
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = -$invoice->customer_paid;
            $this->transaction_balance->create($balance_data);
        }

        // end transaction log

        // Update stock
        $order_items = $this->pos_order_item->where('order_id', $invoice_id)->get();
        foreach ($order_items as $order_item) {

            $product = $this->product->withTrashed()->find($order_item->product_id);
            $product->stock_quantity = $product->stock_quantity + $order_item->quantity;
            $product->save();

            // Stock Log
            $today = Carbon::now()->format('Y-m-d H:i:s');
            $stock_log_data['product_id'] = $product->id;
            $stock_log_data['product_name'] = $order_item->product_name;
            $stock_log_data['reference_id'] = $invoice->id;
            $stock_log_data['reference'] = $invoice->code;
            $stock_log_data['quantity'] = $order_item->quantity;
            $stock_log_data['balance'] = $product->stock_quantity;
            $stock_log_data['cost'] = $product->cost ?? 0;
            $stock_log_data['selling_price'] = $product->selling ?? 0;
            $stock_log_data['reason'] = 'Deleted the invoice';
            $stock_log_data['type'] = StockLog::TYPE['plus'];
            $user = Auth::user();
            $stock_log_data['created_by'] = $user->id;
            $stock_log_data['created_user_name'] = $user->name;
            $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
            $stock_log_data['date'] = $today;
            StockLogFacade::store($stock_log_data);
        }

        return $invoice->delete();
    }

    public function restoreInvoice(int $invoice_id)
    {
        $deleted_invoice = $this->pos_order->withTrashed()->find($invoice_id);

        $transaction_data['code'] = $this->generateNewTRCode('TR');
        $transaction_data['type'] = 2;
        $transaction_data['date'] = Carbon::now();
        $transaction_data['reference_code'] = $deleted_invoice->code;
        $transaction_data['payment_code'] = $deleted_invoice->code;
        $transaction_data['client'] = $deleted_invoice->customer_name;
        $transaction_data['currency_id'] = $deleted_invoice->currency_id;
        $transaction_data['amount'] = $deleted_invoice->customer_paid;
        $transaction_data['sign'] = 1;
        $transaction_data['description'] = 'Invoice Restore';
        $this->transaction->create($transaction_data);

        $transaction_balance = $this->transaction_balance->where('currency_id', $deleted_invoice->currency_id)->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount + $deleted_invoice->customer_paid;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($transaction_data['currency_id']);
            $balance_data['currency_id'] = $transaction_data['currency_id'];
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = $deleted_invoice->customer_paid;
            $this->transaction_balance->create($balance_data);
        }

        // end transaction log

        // Update stock
        $order_items = $this->pos_order_item->where('order_id', $invoice_id)->get();
        foreach ($order_items as $order_item) {

            $product = $this->product->withTrashed()->find($order_item->product_id);
            $product->stock_quantity = $product->stock_quantity - $order_item->quantity;
            $product->save();

            // Stock Log
            $today = Carbon::now()->format('Y-m-d H:i:s');
            $stock_log_data['product_id'] = $product->id;
            $stock_log_data['product_name'] = $order_item->product_name;
            $stock_log_data['reference_id'] = $deleted_invoice->id;
            $stock_log_data['reference'] = $deleted_invoice->code;
            $stock_log_data['quantity'] = $order_item->quantity;
            $stock_log_data['balance'] = $product->stock_quantity;
            $stock_log_data['cost'] = $product->cost ?? 0;
            $stock_log_data['selling_price'] = $product->selling ?? 0;
            $stock_log_data['reason'] = 'Restored the deleted invoice';
            $stock_log_data['type'] = StockLog::TYPE['minus'];
            $user = Auth::user();
            $stock_log_data['created_by'] = $user->id;
            $stock_log_data['created_user_name'] = $user->name;
            $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_out'];
            $stock_log_data['date'] = $today;
            StockLogFacade::store($stock_log_data);
        }

        $deleted_invoice->deleted_at = null;

        return $deleted_invoice->save();
    }

    private function generateNewTRCode($prefix)
    {
        $latest_student = $this->transaction->withTrashed()
            ->where('code', 'LIKE', $prefix.'%')
            ->orderBy('code', 'desc')
            ->first();

        if ($latest_student) {
            $latest_code = substr($latest_student->code, strlen($prefix));
            $next_num = intval($latest_code) + 1;
        } else {
            $next_num = 1;
        }

        $code = $prefix.sprintf('%05d', $next_num);

        return $code;
    }

    public function sendCustomerInvoiceEmail(int $invoice_id, array $data)
    {
        try {
            $order = PosOrder::find($invoice_id);
            $response['order'] = PosOrderFacade::get($invoice_id);
            $response['order_items'] = PosOrderItemFacade::all($invoice_id);
            $response['created_at'] = $response['order']['created_at'];
            $response['print_type'] = 'invoice';
            $response['bill'] = BillPaymentFacade::get($invoice_id);
            $response['config'] = $this->business_details->latest()->first();

            if ($order->type == 0) {
                $pdf = PDF::loadView('print.pages.Bill.payment', $response)->setPaper([0, 0, 300, 700], 'portrait');
            } else {
                $response['custom_fields'] = InvoiceFacade::parametersGetForPrint($invoice_id);
                $response['footer_fields'] = InvoiceFacade::footerParametersGetForPrint($invoice_id);
                $pdf = PDF::loadView('print.pages.Invoice.invoice', $response)->setPaper('a4');
            }

            $pdfContent = $pdf->output();
            $filePath = storage_path('app/temp_invoice.pdf');
            file_put_contents($filePath, $pdfContent);

            $sender_details = $this->business_details->first();
            if (! $sender_details->email) {
                return response()->json(['message' => 'Please enter business email']);
            }

            $image = $sender_details->image_url;

            // if ($sender_details->image_id != null) {
            //     $image = $sender_details->image_url;
            //     // Download the file
            //     $response_img = Http::get($image);
            //     $pngPath = 'temp_logo/temp_logo.png';
            //     Storage::disk('public')->put($pngPath, $response_img->__toString());
            //     $pngUrl = Storage::url($pngPath);
            // }else{
            //     $pngUrl = null;
            // }

            $default_mail = $data['to'];
            $sendData = [
                'sender_email' => $sender_details->email,
                'business_name' => $sender_details->name,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'cc' => $data['cc'],
            ];

            SendCustomerInvoiceMailJob::dispatch($sendData, $default_mail, $filePath, $image);

            // if ($pngPath) {
            //     Storage::disk('public')->delete($pngPath);
            // }

            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function createInvoiceLink(int $invoice_id)
    {
        $invoice = $this->pos_order->findOrFail($invoice_id);
        $key = Str::random(30);
        $invoice->update([
            'invoice_link' => $key,
        ]);
        $url = url('/invoice/view/'.$key);

        return $url;
    }

    public function getPublicInvoice(string $invoice_key)
    {
        $invoice = $this->pos_order->where('invoice_link', $invoice_key)->get();

        return $invoice[0]->id;
    }

    public function calculateTotals($invoices)
    {
        $totals = [
            'total' => 0,
            'paid_amount' => 0,
            'due_amount' => 0,
        ];

        foreach ($invoices as $invoice) {
            $totals['total'] += $invoice['total'];
            $totals['paid_amount'] += $invoice['customer_paid'];
            if ($invoice['total'] - $invoice['customer_paid'] > 0) {
                $totals['due_amount'] += $invoice['total'] - $invoice['customer_paid'];
            } else {
                $totals['due_amount'] += 0;
            }
        }

        return $totals;
    }
}
