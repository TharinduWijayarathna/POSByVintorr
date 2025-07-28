<?php

namespace domain\Services\QuotationService;

use App\Jobs\SendCustomerQuotationMailJob\SendCustomerQuotationMailJob;
use App\Models\BillPayment;
use App\Models\BusinessDetail;
use App\Models\Customer;
use App\Models\InvoiceItemFooterParameter;
use App\Models\InvoiceItemParameter;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Product;
use App\Models\ProductTax;
use App\Models\Quotation;
use App\Models\QuotationFooterParameter;
use App\Models\QuotationItem;
use App\Models\QuotationItemFooterParameter;
use App\Models\QuotationItemParameter;
use App\Models\QuotationParameter;
use App\Models\StockLog;
use Carbon\Carbon;
use domain\Facades\QuotationFacade\QuotationFacade;
use domain\Facades\StockLogFacade\StockLogFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PDF;

class QuotationService
{
    private $product;

    private $quotation;

    private $customer;

    private $bill_payment;

    private $quotation_item;

    private $pos_order;

    private $pos_order_items;

    private $business_details;

    private $quotation_parameter;

    private $quotation_item_parameter;

    private $quotation_footer_parameter;

    private $quotation_item_footer_parameter;

    private $invoice_item_parameter;

    private $invoice_item_footer_parameter;

    private $product_tax;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->product = new Product;
        $this->quotation = new Quotation;
        $this->quotation_item = new QuotationItem;
        $this->customer = new Customer;
        $this->bill_payment = new BillPayment;
        $this->pos_order = new PosOrder;
        $this->pos_order_items = new PosOrderItem;
        $this->business_details = new BusinessDetail;
        $this->quotation_parameter = new QuotationParameter;
        $this->quotation_item_parameter = new QuotationItemParameter;
        $this->quotation_footer_parameter = new QuotationFooterParameter;
        $this->quotation_item_footer_parameter = new QuotationItemFooterParameter;
        $this->invoice_item_parameter = new InvoiceItemParameter;
        $this->invoice_item_footer_parameter = new InvoiceItemFooterParameter;
        $this->product_tax = new ProductTax;
    }

    public function get(int $quotation_id)
    {
        return $this->quotation->find($quotation_id);
    }

    public function create()
    {
        $data['created_by'] = Auth::id();
        $count = $this->quotation->where('code', 'like', 'Q%')->withTrashed()->count();

        $code = 'Q'.sprintf('%05d', $count + 1);
        $check = $this->quotation->getCode($code);

        while ($check) {
            $count++;
            $code = 'Q'.sprintf('%05d', $count);
            $check = $this->quotation->getCode($code);
        }

        $data['code'] = $code;
        $data['date'] = Carbon::today();

        $business_details = $this->business_details->first();
        if ($business_details->currency_id != null) {
            $data['currency_id'] = $business_details->currency_id;
        }

        return $this->quotation->create($data);
    }

    /**
     * getOrCreate
     *
     * @return void
     */
    public function getOrCreate()
    {
        $order = $this->quotation->getQuotationOrder();
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
        $order = $this->quotation->getQuotationOrderNew();
        if ($order) {
            $date = Carbon::today();

            $business_detail = $this->business_details->first();
            $this->quotation_item->where('quotation_id', $order->id)->delete();

            $this->quotation_item_parameter->where('quotation_id', $order->id)->update(['description' => null]);

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
                'ref_no' => null,
                'currency_id' => $business_detail->currency_id,
                'note' => null,
            ]);

            return $order;
        } else {
            return $this->create();
        }
    }

    public function discount(array $data)
    {
        $quotation = $this->quotation->where('id', $data['orderId'])->first();
        if ($quotation) {
            if ($data['discountType'] == 1) {
                $quotation->discount = $data['discountAmount'];
                $quotation->discount_type = 0;
            }
            if ($data['discountType'] == 2) {
                $quotation->discount = $data['discountAmount'];
                $quotation->discount_type = 1;
            }
            $quotation->save();
        }
    }

    public function removeDiscount($quotation_id)
    {
        $quotation = $this->quotation->where('id', $quotation_id)->first();
        if ($quotation) {
            $quotation->discount = 0;
            $quotation->discount_type = null;
            $quotation->save();
        }
    }

    public function storeCustomer(int $quotation_id, int $pos_customer_id)
    {
        $customer = $this->customer->find($pos_customer_id);
        $quotation = $this->quotation->find($quotation_id);
        // $quotation->customer_id = $pos_customer_id;
        // $quotation->customer_name = $customer->name;
        // $quotation->customer_address = $customer->address;
        // $quotation->customer_email = $customer->email;
        // $quotation->customer_mobile = $customer->contact;
        // return $quotation->save();
        if (! $customer) {
            // Handle the case where customer is not found
            $customer = (object) [
                'name' => 'Walking Customer',
                'address' => '',
                'email' => '',
                'contact' => '',
            ];
        }

        if ($quotation->customer_id != $pos_customer_id || $quotation->customer_id == null) {
            $quotation->customer_id = $pos_customer_id;
            $quotation->customer_name = $customer->name;
            $quotation->customer_address = $customer->address;
            $quotation->customer_email = $customer->email;
            $quotation->customer_mobile = $customer->contact;

            return $quotation->save();
        }
    }

    public function storeCurrency(int $quotation_id, int $currency_id)
    {
        $quotation = $this->quotation->find($quotation_id);
        $quotation->currency_id = $currency_id;

        return $quotation->save();
    }

    public function storeRef($data, int $quotation_id)
    {
        $quotation = $this->quotation->find($quotation_id);
        $quotation->ref_no = $data['ref'];

        return $quotation->save();
    }

    public function storeNote(int $quotation_id, $data)
    {
        $quotation = $this->quotation->find($quotation_id);
        $quotation->note = $data['note'];

        return $quotation->save();
    }

    public function getLoyaltyCustomer(int $quotation_id)
    {
        $quotation = $this->quotation->withTrashed()->find($quotation_id);

        return $this->customer->find($quotation->customer_id);
    }

    public function editQuotationCustomer($data, int $quotation_id)
    {
        $quotation = $this->quotation->find($quotation_id);
        $quotation->customer_mobile = $data['customer_mobile'];
        $quotation->customer_email = $data['customer_email'];
        $quotation->customer_address = $data['customer_address'];

        return $quotation->save();
    }

    public function removeCustomerId($quotation_id)
    {
        $quotation = $this->quotation->where('id', $quotation_id)->first();
        if ($quotation) {
            $quotation->customer_id = null;
            $quotation->customer_name = null;
            $quotation->customer_address = null;
            $quotation->customer_email = null;
            $quotation->customer_mobile = null;
            $quotation->save();
        }

        return $quotation;
    }

    public function getRelatedQuotation(int $quotation_id)
    {
        return $this->quotation->withTrashed()->find($quotation_id);
    }

    public function getForDelete(int $quotation_id)
    {
        return $this->quotation->find($quotation_id);
    }

    public function delete(int $quotation_id)
    {
        $quotation = $this->quotation->find($quotation_id);
        $quotation->status = 1;
        $quotation->save();

        return $quotation->delete();
    }

    public function restoreQuotation(int $quotation_id)
    {
        $deleted_quotation = $this->quotation->withTrashed()->find($quotation_id);
        $deleted_quotation->deleted_at = null;

        return $deleted_quotation->save();
    }

    public function selectQuotationProduct($product_data, $quotation_id, $request_data)
    {
        $quotation = $this->quotation->where('id', $quotation_id)->first();
        if ($quotation->currency_id == null) {
            $business_details = BusinessDetail::first();
            $quotation->currency_id = $business_details->currency_id;
            $quotation->save();
        }

        // $quotation_item = $this->quotation_item->where('product_id', $product_data['id'])->where('quotation_id', $quotation_id)->first();
        // if (!empty($quotation_item)) {
        //     $quotation_item->unit_price = $request_data['selling_price'];
        //     $quotation_item->quantity = $quotation_item->quantity + $request_data['quantity'];
        //     $quotation_item->sub_total = $quotation_item->quantity * $request_data['selling_price'];
        //     $quotation_item->total = $quotation_item->quantity * $request_data['selling_price'];
        //     $quotation_item->save();
        // } else {

        // }
        $data = [
            'quotation_id' => $quotation_id,
            'product_id' => $product_data['id'],
            'product_name' => $product_data['name'],
            'description' => $request_data['description'],
            'quantity' => $request_data['quantity'],
            'unit_price' => $request_data['selling_price'],
            'sub_total' => $request_data['quantity'] * $request_data['selling_price'],
            'total' => 1 * $request_data['quantity'] * $request_data['selling_price'],
            'total_tax' => $this->calculateTotalTax($request_data['quantity'], $request_data['selling_price'], $product_data['id']),

        ];
        $this->quotation_item->create($data);
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

    public function getQuotationProduct($quotation_data)
    {
        return $this->quotation_item->getAll($quotation_data['id']);
    }

    public function getTotals($quotation_id)
    {
        $this->recalculateTaxes($quotation_id);

        $subTotal = $this->quotation_item->subTotal($quotation_id);

        return $this->quotation->updateTotals($quotation_id, $subTotal);
    }

    /**
     * recalculateTaxes
     *
     * @param  mixed  $quotation_id
     * @return void
     */
    public function recalculateTaxes($quotation_id)
    {
        $tax_total = $this->quotation_item->totalTax($quotation_id);

        return $this->quotation->updateTaxes($quotation_id, $tax_total);
    }

    public function removeItem($id, int $quotation_id)
    {
        $this->quotation_item->find($id)->delete();
    }

    public function getOrderItem($order_item_id, $quotation_id)
    {
        return $this->quotation_item->find($order_item_id);
    }

    public function updateQty($data, $order_item_id, int $quotation_id)
    {
        $quotation = $this->quotation_item->find($order_item_id);
        if ($quotation) {
            $quotation->product_name = $data['product_name'];
            $quotation->description = $data['description'];
            $quotation->quantity = $data['quantity'];
            $quotation->unit_price = $data['unit_price'];
            $quotation->sub_total = $quotation->quantity * $quotation->unit_price;
            $quotation->total = $quotation->quantity * $quotation->unit_price;
            $quotation->total_tax = $this->calculateTotalTax($quotation->quantity, $quotation->unit_price, $quotation->product_id);
            $quotation->save();
        }
    }

    public function getQuotationItems($quotation_id)
    {
        return $this->quotation_item->getAll($quotation_id);
    }

    public function convertToInvoice(int $quotation_id)
    {
        $today = \Carbon\Carbon::now()->toDateString();
        $quotation = $this->quotation->find($quotation_id);
        $data['created_by'] = Auth::id();
        $count = $this->pos_order->where('code', 'like', 'INV%')->withTrashed()->count();

        $code = 'INV'.sprintf('%05d', $count + 1);
        $check = $this->pos_order->getCode($code);

        while ($check) {
            $count++;
            $code = 'INV'.sprintf('%05d', $count);
            $check = $this->pos_order->getCode($code);
        }

        $data['code'] = $code;
        $data['status'] = 2;
        $data['type'] = 1;
        $data['customer_id'] = $quotation->customer_id;
        $data['customer_name'] = $quotation->customer_name;
        $data['customer_mobile'] = $quotation->customer_mobile;
        $data['customer_address'] = $quotation->customer_address;
        $data['customer_email'] = $quotation->customer_email;
        $data['ref_no'] = $quotation->ref_no;
        $data['currency_id'] = $quotation->currency_id;
        $data['note'] = $quotation->note;
        $data['sub_total'] = $quotation->sub_total;
        $data['total'] = $quotation->total;
        $data['total_tax'] = $quotation->total_tax;
        $data['discount'] = $quotation->discount;
        $data['discount_type'] = $quotation->discount_type;
        $data['date'] = $today;
        $posOrder = $this->pos_order->create($data);
        $posOrderId = $posOrder->id;

        $quotationProducts = $this->quotation_item->where('quotation_id', $quotation_id)->get();

        $quotation_item_parameters = $this->quotation_item_parameter->where('quotation_id', $quotation_id)->get();

        foreach ($quotation_item_parameters as $quotation_item_parameter) {

            $quotationItemParameters = [
                'invoice_id' => $posOrderId,
                'name' => $quotation_item_parameter->name,
                'description' => $quotation_item_parameter->description,
                'parameter_id' => $quotation_item_parameter->parameter_id,
                'order' => $quotation_item_parameter->order,
            ];

            $this->invoice_item_parameter->create($quotationItemParameters);
        }

        $quotation_item_footer_parameters = $this->quotation_item_footer_parameter->where('quotation_id', $quotation_id)->get();

        foreach ($quotation_item_footer_parameters as $quotation_item_footer_parameter) {

            $quotationItemFooterParameters = [
                'invoice_id' => $posOrderId,
                'name' => $quotation_item_footer_parameter->name,
                'description' => $quotation_item_footer_parameter->description,
                'parameter_id' => $quotation_item_footer_parameter->parameter_id,
                'order' => $quotation_item_footer_parameter->order,
            ];

            $this->invoice_item_footer_parameter->create($quotationItemFooterParameters);
        }

        $orderTotal = 0;
        $orderTotalTax = 0;
        foreach ($quotationProducts as $quotationProduct) {
            $posOrderItemData = [
                'order_id' => $posOrderId,
                'product_id' => $quotationProduct->product_id,
                'product_name' => $quotationProduct->product_name,
                'description' => $quotationProduct->description,
                'quantity' => $quotationProduct->quantity,
                'unit_price' => $quotationProduct->unit_price,
                'sub_total' => $quotationProduct->total,
                'total' => $quotationProduct->total,
                'total_tax' => $quotationProduct->total_tax,
            ];

            $orderTotal = $orderTotal + $quotationProduct->total;
            $orderTotalTax = $orderTotalTax + $quotationProduct->total_tax;

            $this->pos_order_items->create($posOrderItemData);

            $product = $this->product->withTrashed()->find($quotationProduct->product_id);
            if ($product->product_type == $this->product::PRODUCT_TYPE['stockable']) {
                $product->stock_quantity = $product->stock_quantity - $quotationProduct->quantity;
                $product->save();

                // Stock Log
                $today = Carbon::now()->format('Y-m-d H:i:s');
                $stock_log_data['product_id'] = $product->id;
                $stock_log_data['product_name'] = $quotationProduct->product_name;
                $stock_log_data['reference_id'] = $posOrder->id;
                $stock_log_data['reference'] = $posOrder->code;
                $stock_log_data['quantity'] = $quotationProduct->quantity ?? 0;
                $stock_log_data['balance'] = $product->stock_quantity ?? 0;
                $stock_log_data['cost'] = $product->cost ?? 0;
                $stock_log_data['selling_price'] = $product->selling ?? 0;
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

        $posOrder->sub_total = $orderTotal;
        $posOrder->total = $orderTotal;
        $posOrder->total_tax = $orderTotalTax;
        $posOrder->save();

        $quotation->convert_status = 1;
        $quotation->save();

        return $posOrderId;
    }

    /**
     * StoreFooterParameter
     * create new footer parameter
     *
     *
     * @return void
     */
    public function storeFooterParameter(int $quotation_id, $data)
    {
        $ip_count = $this->quotation_item_footer_parameter->where('quotation_id', $quotation_id)->count();
        $created_footer_parameter = $this->quotation_footer_parameter->create($data);
        $this->quotation_item_footer_parameter->create([
            'quotation_id' => $quotation_id,
            'name' => $created_footer_parameter->title,
            'parameter_id' => $created_footer_parameter->id,
            'order' => $ip_count + 1,
        ]);

    }

    /**
     * EditFooterParameter
     * edit footer parameter name using quotation id
     *
     *
     * @return void
     */
    public function editFooterParameter($id)
    {
        return $this->quotation_item_footer_parameter->find($id);
    }

    /**
     * GetFooterParameters
     * get specific footer parameter using quotation_id
     *
     *
     * @return void
     */
    public function getFooterParameters(int $quotation_id)
    {
        $quotation_footer_parameters = $this->quotation_footer_parameter->get();
        $quotation_item_footer_parameters = $this->quotation_item_footer_parameter->where('quotation_id', $quotation_id)->get();
        if ($quotation_item_footer_parameters->isEmpty()) {

            if ($quotation_footer_parameters->count() > 0) {
                foreach ($quotation_footer_parameters as $quotation_footer_parameter) {
                    $ip_count = $this->quotation_item_footer_parameter->where('quotation_id', $quotation_id)->count();
                    $this->quotation_item_footer_parameter->create([
                        'quotation_id' => $quotation_id,
                        'name' => $quotation_footer_parameter->title,
                        'description' => $quotation_footer_parameter->description,
                        'parameter_id' => $quotation_footer_parameter->id,
                        'order' => $ip_count + 1,
                    ]);
                }

                return $this->quotation_item_footer_parameter->where('quotation_id', $quotation_id)->orderBy('order', 'asc')->get();
            }
        } else {

            return $quotation_item_footer_parameters;
        }
    }

    /**
     * SetFooterDescription
     * save footer description
     *
     *
     * @return void
     */
    public function setFooterDescription($data)
    {
        $quotation_item_footer_parameters = $this->quotation_item_footer_parameter->find($data['id']);
        $quotation_item_footer_parameters->description = $data['description'];
        $quotation_item_footer_parameters->save();

        $quotation_footer_parameter = $this->quotation_footer_parameter->find($quotation_item_footer_parameters->parameter_id);
        if ($quotation_footer_parameter) {
            $quotation_footer_parameter->description = $data['description'];
            $quotation_footer_parameter->save();
        }

    }

    /**
     * UpdateFooterParameter
     * update exist footer parameter
     *
     *
     * @return void
     */
    public function updateFooterParameter($data)
    {
        $quotation_item_footer_parameter = $this->quotation_item_footer_parameter->find($data['id']);
        $quotation_item_footer_parameter->name = $data['title'];
        $quotation_item_footer_parameter->save();

        $quotation_footer_parameter = $this->quotation_footer_parameter->find($quotation_item_footer_parameter->parameter_id);
        if ($quotation_footer_parameter) {
            $quotation_footer_parameter->title = $data['title'];
            $quotation_footer_parameter->save();
        }
    }

    /**
     * DeleteFooterParameter
     * delete specific footer parameter using id
     *
     *
     * @return void
     */
    public function deleteFooterParameter($id)
    {
        $quotation_item_footer_parameter = $this->quotation_item_footer_parameter->find($id);
        $quotation_footer_parameter = $this->quotation_footer_parameter->find($quotation_item_footer_parameter->parameter_id);
        if ($quotation_footer_parameter) {
            $quotation_footer_parameter->delete();
        }

        return $quotation_item_footer_parameter->delete();
    }

    // Header Parameter
    /**
     * StoreParameter
     * store the parameter details to the database
     *
     *
     * @return void
     */
    public function storeParameter(int $quotation_id, $data)
    {
        $ip_count = $this->quotation_item_parameter->where('quotation_id', $quotation_id)->count();
        $created_parameter = $this->quotation_parameter->create($data);
        $this->quotation_item_parameter->create([
            'quotation_id' => $quotation_id,
            'name' => $created_parameter->title,
            'parameter_id' => $created_parameter->id,
            'order' => $ip_count + 1,
        ]);

    }

    /**
     * GetParameters
     * get specific parameter details using quotation_id
     *
     *
     * @return void
     */
    public function getParameters(int $quotation_id)
    {
        $quotation_parameters = $this->quotation_parameter->get();
        $quotation_item_parameters = $this->quotation_item_parameter->where('quotation_id', $quotation_id)->get();
        if ($quotation_item_parameters->isEmpty()) {

            if ($quotation_parameters->count() > 0) {
                foreach ($quotation_parameters as $quotation_parameter) {
                    $ip_count = $this->quotation_item_parameter->where('quotation_id', $quotation_id)->count();
                    $this->quotation_item_parameter->create([
                        'quotation_id' => $quotation_id,
                        'name' => $quotation_parameter->title,
                        'parameter_id' => $quotation_parameter->id,
                        'order' => $ip_count + 1,
                    ]);
                }

                return $this->quotation_item_parameter->where('quotation_id', $quotation_id)->orderBy('order', 'asc')->get();
            }
        } else {

            return $quotation_item_parameters;
        }
    }

    /**
     * SetDescription
     * set the description for the header parameters
     *
     *
     * @return void
     */
    public function setDescription($data)
    {
        $quotation_item_parameters = $this->quotation_item_parameter->find($data['id']);
        $quotation_item_parameters->description = $data['description'];

        return $quotation_item_parameters->save();
    }

    /**
     * EditParameter
     * edit the exist parameter using id
     *
     *
     * @return void
     */
    public function editParameter($id)
    {
        return $this->quotation_item_parameter->find($id);
    }

    /**
     * UpdateParameter
     * update exist parameter
     *
     *
     * @return void
     */
    public function updateParameter($data)
    {
        $quotation_item_parameter = $this->quotation_item_parameter->find($data['id']);
        $quotation_item_parameter->name = $data['title'];
        $quotation_item_parameter->save();

        $quotation_parameter = $this->quotation_parameter->find($quotation_item_parameter->parameter_id);
        if ($quotation_parameter) {
            $quotation_parameter->title = $data['title'];
            $quotation_parameter->save();
        }
    }

    /**
     * DeleteParameter
     * delete the specific parameter
     *
     *
     * @return void
     */
    public function deleteParameter($id)
    {
        $quotation_item_parameter = $this->quotation_item_parameter->find($id);
        $quotation_parameter = $this->quotation_parameter->find($quotation_item_parameter->parameter_id);
        if ($quotation_parameter) {
            $quotation_parameter->delete();
        }

        return $quotation_item_parameter->delete();
    }

    /**
     * ParametersGetForPrint
     * get header parameter for the print
     *
     * @param  int  $quotation_id  [explicite description]
     * @return void
     */
    public function parametersGetForPrint(int $quotation_id)
    {
        return $this->quotation_item_parameter->where('quotation_id', $quotation_id)->where('description', '!=', 'null')->get();
    }

    /**
     * FooterParametersGetForPrint
     * get footer parameter for the print
     *
     *
     * @return void
     */
    public function footerParametersGetForPrint(int $quotation_id)
    {
        return $this->quotation_item_footer_parameter->where('quotation_id', $quotation_id)->where('description', '!=', 'null')->get();
    }

    public function sendCustomerQuotationEmail(int $quotation_id, array $data)
    {
        try {
            $response['quotation'] = QuotationFacade::get($quotation_id);
            $response['quotation_items'] = QuotationFacade::allItems($quotation_id);
            $response['created_at'] = $response['quotation']['created_at'];
            $response['print_type'] = 'quotation';
            $response['config'] = $this->business_details->orderBy('id', 'desc')->first();

            $response['custom_fields'] = QuotationFacade::parametersGetForPrint($quotation_id);
            $response['footer_fields'] = QuotationFacade::footerParametersGetForPrint($quotation_id);

            $pdf = PDF::loadView('print.pages.Quotation.quotation', $response)->setPaper('a4');

            $pdfContent = $pdf->output();
            $filePath = storage_path('app/temp_invoice.pdf');
            file_put_contents($filePath, $pdfContent);

            $sender_details = $this->business_details->first();
            if (! $sender_details->email) {
                return response()->json(['message' => 'Please enter business email']);
            }

            $image = $sender_details->image_url;

            $default_mail = $data['to'];
            $sendData = [
                'sender_email' => $sender_details->email,
                'business_name' => $sender_details->name,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'cc' => $data['cc'],
            ];

            SendCustomerQuotationMailJob::dispatch($sendData, $default_mail, $filePath, $image);

            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function createQuotationLink(int $quotation_id)
    {
        $quotation = $this->quotation->findOrFail($quotation_id);
        $key = str::random(30);
        $quotation->update([
            'quotation_link' => $key,
        ]);
        $url = url('/quotation/view/'.$key);

        return $url;
    }

    public function getPublicQuotation(string $quotation_key)
    {
        $quotation = $this->quotation->where('quotation_link', $quotation_key)->get();

        return $quotation[0]->id;
    }

    public function allItems(int $quotation_id)
    {
        return $this->quotation_item->getAll($quotation_id);
    }
}
