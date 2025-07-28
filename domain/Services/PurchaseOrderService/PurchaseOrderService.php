<?php

namespace domain\Services\PurchaseOrderService;

use App\Jobs\SendSupplierPurchaseOrderMailJob\SendSupplierPurchaseOrderMailJob;
use App\Models\BusinessDetail;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderFooterParameter;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderItemFooterParameter;
use App\Models\PurchaseOrderItemParameter;
use App\Models\PurchaseOrderParameter;
use App\Models\Supplier;
use PDF;
use Carbon\Carbon;
use domain\Facades\PurchaseOrderFacade\PurchaseOrderFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PurchaseOrderService
{

    private $purchase_order;
    private $supplier;
    private $purchase_order_item;
    private $business_details;
    private $purchase_order_parameter;
    private $purchase_order_item_parameter;
    private $purchase_order_footer_parameter;
    private $purchase_order_item_footer_parameter;


    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->purchase_order = new PurchaseOrder();
        $this->purchase_order_item = new PurchaseOrderItem();
        $this->supplier = new Supplier();
        $this->business_details = new BusinessDetail();
        $this->purchase_order_parameter = new PurchaseOrderParameter();
        $this->purchase_order_item_parameter = new PurchaseOrderItemParameter();
        $this->purchase_order_footer_parameter = new PurchaseOrderFooterParameter();
        $this->purchase_order_item_footer_parameter = new PurchaseOrderItemFooterParameter();
    }

    /**
     * get
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function get(int $purchase_order_id)
    {
        return $this->purchase_order->find($purchase_order_id);
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        $data['created_by'] = Auth::id();
        $data['date'] = Carbon::today();
        $count = $this->purchase_order->where('code', 'like', "PO%")->withTrashed()->count();

        $code = 'PO' . sprintf('%05d', $count + 1);
        $check = $this->purchase_order->getCode($code);

        while ($check) {
            $count++;
            $code = 'PO' . sprintf('%05d', $count);
            $check = $this->purchase_order->getCode($code);
        }

        $data['code'] = $code;

        $business_details = $this->business_details->first();
        if ($business_details->currency_id != null) {
            $data['currency_id'] = $business_details->currency_id;
        }
        return $this->purchase_order->create($data);
    }

    /**
     * getOrCreate
     *
     * @return void
     */
    public function getOrCreate()
    {
        // checking status 0

        $order = $this->purchase_order->getPurchaseOrder();
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
        // checking status 0

        $order = $this->purchase_order->getPurchaseOrderNew();
        if ($order) {
            $date = Carbon::today();

            $business_detail = $this->business_details->first();
            $this->purchase_order_item->where('purchase_order_id', $order->id)->delete();

            $this->purchase_order_item_parameter->where('purchase_order_id', $order->id)->update(['description' => null]);

            $order->update([
                'date' => $date,
                'created_at' => $date,
                'updated_at' => $date,
                'supplier_id' => null,
                'supplier_name' => null,
                'supplier_address' => null,
                'supplier_email' => null,
                'supplier_mobile' => null,
                'total' => 0,
                'ref_no' => null,
                'note' => null,
                'currency_id' => $business_detail->currency_id,
            ]);
            return $order;
        } else {
            return $this->create();
        }
    }

    /**
     * storePODate
     *
     * @param  mixed $purchase_order_id
     * @param  mixed $data
     * @return void
     */
    public function storePODate(int $purchase_order_id, $data)
    {
        $dateString = $data['date'];
        $date = Carbon::parse($dateString);
        $purchase_order = $this->purchase_order->find($purchase_order_id);
        $purchase_order->date = $date;
        return $purchase_order->save();
    }

    /**
     * storeSupplier
     *
     * @param  mixed $purchase_order_id
     * @param  mixed $pos_supplier_id
     * @return void
     */
    public function storeSupplier(int $purchase_order_id, int $pos_supplier_id)
    {
        $supplier = $this->supplier->find($pos_supplier_id);
        $purchase_order = $this->purchase_order->find($purchase_order_id);

        if ($purchase_order->supplier_id != $pos_supplier_id || $purchase_order->supplier_id == null) {
            $purchase_order->supplier_id = $pos_supplier_id;
            $purchase_order->supplier_name = $supplier->name;
            $purchase_order->supplier_address = $supplier->address;
            $purchase_order->supplier_email = $supplier->email;
            $purchase_order->supplier_mobile = $supplier->contact;
            return $purchase_order->save();
        }
    }

    /**
     * getPurchaseOrderSupplier
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function getPurchaseOrderSupplier($purchase_order_id)
    {
        $purchase_order = $this->purchase_order->withTrashed()->find($purchase_order_id);
        return $this->supplier->find($purchase_order->supplier_id);
    }

    /**
     * storeCurrency
     *
     * @param  mixed $purchase_order_id
     * @param  mixed $currency_id
     * @return void
     */
    public function storeCurrency(int $purchase_order_id, int $currency_id)
    {
        $purchase_order = $this->purchase_order->find($purchase_order_id);
        $purchase_order->currency_id = $currency_id;
        return $purchase_order->save();
    }

    /**
     * storeRef
     *
     * @param  mixed $data
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function storeRef($data, int $purchase_order_id)
    {
        $purchase_order = $this->purchase_order->find($purchase_order_id);
        $purchase_order->ref_no = $data['ref'];
        return $purchase_order->save();
    }

    /**
     * storeNote
     *
     * @param  mixed $purchase_order_id
     * @param  mixed $data
     * @return void
     */
    public function storeNote(int $purchase_order_id, $data)
    {
        $purchase_order = $this->purchase_order->find($purchase_order_id);
        $purchase_order->note = $data['note'];
        return $purchase_order->save();
    }

    /**
     * editPurchaseOrderSupplier
     *
     * @param  mixed $data
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function editPurchaseOrderSupplier($data, int $purchase_order_id)
    {
        $purchase_order = $this->purchase_order->find($purchase_order_id);
        $purchase_order->supplier_mobile = $data['supplier_mobile'];
        $purchase_order->supplier_email = $data['supplier_email'];
        $purchase_order->supplier_address = $data['supplier_address'];
        return $purchase_order->save();
    }

    /**
     * removeSupplierId
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function removeSupplierId($purchase_order_id)
    {
        $purchase_order = $this->purchase_order->where('id', $purchase_order_id)->first();
        if ($purchase_order) {
            $purchase_order->supplier_id = null;
            $purchase_order->supplier_name = null;
            $purchase_order->supplier_address = null;
            $purchase_order->supplier_email = null;
            $purchase_order->supplier_mobile = null;
            $purchase_order->save();
        }
        return $purchase_order;
    }

    /**
     * getRelatedPurchaseOrder
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function getRelatedPurchaseOrder(int $purchase_order_id)
    {
        return $this->purchase_order->withTrashed()->find($purchase_order_id);
    }

    /**
     * getForDelete
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function getForDelete(int $purchase_order_id)
    {
        return $this->purchase_order->find($purchase_order_id);
    }

    /**
     * delete
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function delete(int $purchase_order_id)
    {
        $purchase_order = $this->purchase_order->find($purchase_order_id);
        $purchase_order->status = 1;
        $purchase_order->save();
        return $purchase_order->delete();
    }

    /**
     * restorePurchaseOrder
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function restorePurchaseOrder(int $purchase_order_id)
    {
        $deleted_purchase_order = $this->purchase_order->withTrashed()->find($purchase_order_id);
        $deleted_purchase_order->deleted_at = null;
        return $deleted_purchase_order->save();
    }

    /**
     * selectPurchaseOrderProduct
     *
     * @param  mixed $product_data
     * @param  mixed $purchase_order_id
     * @param  mixed $request_data
     * @return void
     */
    public function selectPurchaseOrderProduct($product_data, $purchase_order_id, $request_data)
    {
        $purchase_order = $this->purchase_order->where('id', $purchase_order_id)->first();
        if ($purchase_order->currency_id == null) {
            $business_details = BusinessDetail::first();
            $purchase_order->currency_id = $business_details->currency_id;
            $purchase_order->save();
        }

        $data = [
            'purchase_order_id' => $purchase_order_id,
            'product_id' => $product_data['id'],
            'product_name' => $product_data['name'],
            'description' => $request_data['description'],
            'quantity' => $request_data['quantity'],
            'cost' => $request_data['cost'],
            'total' => $request_data['quantity'] * $request_data['cost'],
        ];
        $this->purchase_order_item->create($data);
    }

    /**
     * getPurchaseOrderProduct
     *
     * @param  mixed $purchase_order_data
     * @return void
     */
    public function getPurchaseOrderProduct($purchase_order_data)
    {
        return $this->purchase_order_item->getAll($purchase_order_data['id']);
    }

    /**
     * getTotals
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function getTotals($purchase_order_id)
    {
        $total = $this->purchase_order_item->total($purchase_order_id);
        return $this->purchase_order->updateTotals($purchase_order_id, $total);
    }

    /**
     * removeItem
     *
     * @param  mixed $product_id
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function removeItem($order_item_id, int $purchase_order_id)
    {
        $this->purchase_order_item->find($order_item_id)->delete();
    }

    /**
     * getOrderItem
     *
     * @param  mixed $id
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function getOrderItem($id, $purchase_order_id)
    {
        return $this->purchase_order_item->find($id);
    }

    /**
     * updateQty
     *
     * @param  mixed $data
     * @param  mixed $product_data
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function updateQty($data, $order_item_id, int $purchase_order_id)
    {
        $purchase_order = $this->purchase_order_item->find($order_item_id);
        if ($purchase_order) {
            $purchase_order->product_name = $data['product_name'];
            $purchase_order->quantity = $data['quantity'];
            $purchase_order->cost = $data['cost'];
            $purchase_order->total = $purchase_order->quantity * $purchase_order->cost;
            $purchase_order->save();
        }
    }

    /**
     * getPurchaseOrderItems
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function getPurchaseOrderItems($purchase_order_id)
    {
        return $this->purchase_order_item->getAll($purchase_order_id);
    }

    /**
     * StoreFooterParameter
     * create new footer parameter
     *
     * @param int $purchase_order_id
     * @param $data
     *
     * @return void
     */
    public function storeFooterParameter(int $purchase_order_id, $data)
    {
        $ip_count = $this->purchase_order_item_footer_parameter->where('purchase_order_id', $purchase_order_id)->count();
        $created_footer_parameter = $this->purchase_order_footer_parameter->create($data);
        $this->purchase_order_item_footer_parameter->create([
            'purchase_order_id' => $purchase_order_id,
            'name' => $created_footer_parameter->title,
            'parameter_id' => $created_footer_parameter->id,
            'order' => $ip_count + 1,
        ]);
        return;
    }

    /**
     * EditFooterParameter
     * edit footer parameter name using purchase_order id
     *
     * @param $id
     *
     * @return void
     */
    public function editFooterParameter($id)
    {
        return $this->purchase_order_item_footer_parameter->find($id);
    }

    /**
     * GetFooterParameters
     * get specific footer parameter using purchase_order_id
     *
     * @param int $purchase_order_id
     *
     * @return void
     */
    public function getFooterParameters(int $purchase_order_id)
    {
        $purchase_order_footer_parameters = $this->purchase_order_footer_parameter->get();
        $purchase_order_item_footer_parameters = $this->purchase_order_item_footer_parameter->where('purchase_order_id', $purchase_order_id)->get();
        if ($purchase_order_item_footer_parameters->isEmpty()) {

            if ($purchase_order_footer_parameters->count() > 0) {
                foreach ($purchase_order_footer_parameters as $purchase_order_footer_parameter) {
                    $ip_count = $this->purchase_order_item_footer_parameter->where('purchase_order_id', $purchase_order_id)->count();
                    $this->purchase_order_item_footer_parameter->create([
                        'purchase_order_id' => $purchase_order_id,
                        'name' => $purchase_order_footer_parameter->title,
                        'description' => $purchase_order_footer_parameter->description,
                        'parameter_id' => $purchase_order_footer_parameter->id,
                        'order' => $ip_count + 1,
                    ]);
                }
                return $this->purchase_order_item_footer_parameter->where('purchase_order_id', $purchase_order_id)->orderBy('order', 'asc')->get();
            }
        } else {

            return $purchase_order_item_footer_parameters;
        }
    }

    /**
     * SetFooterDescription
     * save footer description
     *
     * @param $data
     *
     * @return void
     */
    public function setFooterDescription($data)
    {
        $purchase_order_item_footer_parameters = $this->purchase_order_item_footer_parameter->find($data['id']);
        $purchase_order_item_footer_parameters->description = $data['description'];
        $purchase_order_item_footer_parameters->save();

        $purchase_order_footer_parameter = $this->purchase_order_footer_parameter->find($purchase_order_item_footer_parameters->parameter_id);
        if ($purchase_order_footer_parameter) {
            $purchase_order_footer_parameter->description = $data['description'];
            $purchase_order_footer_parameter->save();
        }
        return;
    }

    /**
     * UpdateFooterParameter
     * update exist footer parameter
     *
     * @param $data
     *
     * @return void
     */
    public function updateFooterParameter($data)
    {
        $purchase_order_item_footer_parameter = $this->purchase_order_item_footer_parameter->find($data['id']);
        $purchase_order_item_footer_parameter->name = $data['title'];
        $purchase_order_item_footer_parameter->save();

        $purchase_order_footer_parameter = $this->purchase_order_footer_parameter->find($purchase_order_item_footer_parameter->parameter_id);
        if ($purchase_order_footer_parameter) {
            $purchase_order_footer_parameter->title = $data['title'];
            $purchase_order_footer_parameter->save();
        }
    }

    /**
     * DeleteFooterParameter
     * delete specific footer parameter using id
     *
     * @param $id
     *
     * @return void
     */
    public function deleteFooterParameter($id)
    {
        $purchase_order_item_footer_parameter = $this->purchase_order_item_footer_parameter->find($id);
        $purchase_order_footer_parameter = $this->purchase_order_footer_parameter->find($purchase_order_item_footer_parameter->parameter_id);
        if ($purchase_order_footer_parameter) {
            $purchase_order_footer_parameter->delete();
        }
        return $purchase_order_item_footer_parameter->delete();
    }

    // Header Parameter
    /**
     * StoreParameter
     * store the parameter details to the database
     *
     * @param int $purchase_order_id
     * @param $data
     *
     * @return void
     */
    public function storeParameter(int $purchase_order_id, $data)
    {
        $ip_count = $this->purchase_order_item_parameter->where('purchase_order_id', $purchase_order_id)->count();
        $created_parameter = $this->purchase_order_parameter->create($data);
        $this->purchase_order_item_parameter->create([
            'purchase_order_id' => $purchase_order_id,
            'name' => $created_parameter->title,
            'parameter_id' => $created_parameter->id,
            'order' => $ip_count + 1,
        ]);
        return;
    }

    /**
     * GetParameters
     * get specific parameter details using purchase_order_id
     *
     * @param int $purchase_order_id
     *
     * @return void
     */
    public function getParameters(int $purchase_order_id)
    {
        $purchase_order_parameters = $this->purchase_order_parameter->get();
        $purchase_order_item_parameters = $this->purchase_order_item_parameter->where('purchase_order_id', $purchase_order_id)->get();
        if ($purchase_order_item_parameters->isEmpty()) {

            if ($purchase_order_parameters->count() > 0) {
                foreach ($purchase_order_parameters as $purchase_order_parameter) {
                    $ip_count = $this->purchase_order_item_parameter->where('purchase_order_id', $purchase_order_id)->count();
                    $this->purchase_order_item_parameter->create([
                        'purchase_order_id' => $purchase_order_id,
                        'name' => $purchase_order_parameter->title,
                        'parameter_id' => $purchase_order_parameter->id,
                        'order' => $ip_count + 1,
                    ]);
                }
                return $this->purchase_order_item_parameter->where('purchase_order_id', $purchase_order_id)->orderBy('order', 'asc')->get();
            }
        } else {

            return $purchase_order_item_parameters;
        }
    }

    /**
     * SetDescription
     * set the description for the header parameters
     *
     * @param $data
     *
     * @return void
     */
    public function setDescription($data)
    {
        $purchase_order_item_parameters = $this->purchase_order_item_parameter->find($data['id']);
        $purchase_order_item_parameters->description = $data['description'];
        return $purchase_order_item_parameters->save();
    }

    /**
     * EditParameter
     * edit the exist parameter using id
     *
     * @param $id
     *
     * @return void
     */
    public function editParameter($id)
    {
        return $this->purchase_order_item_parameter->find($id);
    }

    /**
     * UpdateParameter
     * update exist parameter
     *
     * @param $data
     *
     * @return void
     */
    public function updateParameter($data)
    {
        $purchase_order_item_parameter = $this->purchase_order_item_parameter->find($data['id']);
        $purchase_order_item_parameter->name = $data['title'];
        $purchase_order_item_parameter->save();

        $purchase_order_parameter = $this->purchase_order_parameter->find($purchase_order_item_parameter->parameter_id);
        if ($purchase_order_parameter) {
            $purchase_order_parameter->title = $data['title'];
            $purchase_order_parameter->save();
        }
    }

    /**
     * DeleteParameter
     * delete the specific parameter
     *
     * @param $id
     *
     * @return void
     */
    public function deleteParameter($id)
    {
        $purchase_order_item_parameter = $this->purchase_order_item_parameter->find($id);
        $purchase_order_parameter = $this->purchase_order_parameter->find($purchase_order_item_parameter->parameter_id);
        if ($purchase_order_parameter) {
            $purchase_order_parameter->delete();
        }
        return $purchase_order_item_parameter->delete();
    }

    /**
     * ParametersGetForPrint
     * get header parameter for the print
     *
     * @param int $purchase_order_id [explicite description]
     *
     * @return void
     */
    public function parametersGetForPrint(int $purchase_order_id)
    {
        return $this->purchase_order_item_parameter->where('purchase_order_id', $purchase_order_id)->where('description', '!=', 'null')->get();
    }

    /**
     * FooterParametersGetForPrint
     * get footer parameter for the print
     *
     * @param int $purchase_order_id
     *
     * @return void
     */
    public function footerParametersGetForPrint(int $purchase_order_id)
    {
        return $this->purchase_order_item_footer_parameter->where('purchase_order_id', $purchase_order_id)->where('description', '!=', 'null')->get();
    }


    /**
     * sendSupplierPurchaseOrderEmail
     *
     * @param  mixed $purchaseOrder_id
     * @param  mixed $data
     * @return void
     */
    public function sendSupplierPurchaseOrderEmail(int $purchaseOrder_id, array $data)
    {
        try {
            $response['purchase_order'] = PurchaseOrderFacade::get($purchaseOrder_id);
            $response['created_at'] = $response['purchase_order']['created_at'];
            $response['print_type'] = "po";
            $response['config'] = $this->business_details->orderBy('id', 'desc')->first();

            $response['custom_fields'] = PurchaseOrderFacade::parametersGetForPrint($purchaseOrder_id);
            $response['footer_fields'] = PurchaseOrderFacade::footerParametersGetForPrint($purchaseOrder_id);

            $pdf = PDF::loadView('print.pages.PurchaseOrder.purchase-order', $response)->setPaper('a4');

            $pdfContent = $pdf->output();
            $filePath = storage_path('app/temp_invoice.pdf');
            file_put_contents($filePath, $pdfContent);

            $sender_details = $this->business_details->first();
            if (!$sender_details->email) {
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

            SendSupplierPurchaseOrderMailJob::dispatch($sendData, $default_mail, $filePath, $image);
            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * CreatePurchaseOrderLink
     *
     * @param int $invoice_id
     *
     * @return void
     */
    public function createPurchaseOrderLink(int $purchase_order_id)
    {
        $invoice = $this->purchase_order->findOrFail($purchase_order_id);
        $key = str::random(30);
        $invoice->update([
            'purchase_order_link' => $key
        ]);
        $url = url('/purchase-order/view/' . $key);
        return $url;
    }

    public function getPublicPurchaseOrder(string $purchase_order_key)
    {
        $purchase_order = $this->purchase_order->where('purchase_order_link', $purchase_order_key)->get();
        return $purchase_order[0]->id;
    }

}
