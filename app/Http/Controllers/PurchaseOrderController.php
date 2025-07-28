<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\CreateParameterDescriptionRequest;
use App\Http\Requests\PurchaseOrder\CreateFooterParameterRequest;
use App\Http\Requests\PurchaseOrder\CreateParameterRequest;
use App\Http\Requests\PurchaseOrder\CreatePOItemRequest;
use App\Http\Requests\PurchaseOrder\RefRequest;
use App\Http\Requests\PurchaseOrder\SendSupplierPurchaseOrderEmailRequest;
use App\Http\Requests\PurchaseOrder\UpdateFooterParameterRequest;
use App\Http\Requests\PurchaseOrder\UpdateParameterRequest;
use App\Http\Requests\PurchaseOrder\UpdatePOItemQtyRequest;
use App\Http\Resources\DataResource;
use App\Models\BusinessDetail;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItemFooterParameter;
use App\Models\PurchaseOrderItemParameter;
use App\Models\User;
use domain\Facades\ProductFacade\ProductFacade;
use domain\Facades\PurchaseOrderFacade\PurchaseOrderFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use PDF;

class PurchaseOrderController extends Controller
{
    /**
     * view
     *
     * @return void
     */
    public function view()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $pending_po = PurchaseOrder::where('created_by', Auth::id())->where('status', 0)->first();
            // if ($pending_po) {
            //     if ($pending_po->total > 0) {
            //         $pending_po->status = 1;
            //         $pending_po->save();
            //     }
            // }
            // $response = UserFacade::retrieveHost();
            return Inertia::render('PurchaseOrder/view');
        }
    }

    /**
     * deletedList
     *
     * @return void
     */
    public function deletedList()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('PurchaseOrder/deleted');
        }
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $purchaseOrder = PurchaseOrderFacade::getOrCreateNew();
            return redirect()->route('purchaseOrder.process', $purchaseOrder->id);
        }
    }

    /**
     * process
     *
     * @return void
     */
    public function process($purchase_order_id)
    {
        // $response = UserFacade::retrieveHost();
        $response['purchase_order_id'] = $purchase_order_id;
        return Inertia::render('PurchaseOrder/edit', $response);
    }

    /**
     * all
     *
     * @param  mixed $request
     * @return void
     */
    public function all(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) { {
                $query = PurchaseOrder::query();
                if (isset($request->sorting_value)) {
                    if ($request->sorting_value == 1) {
                        $query->orderBy('code', 'desc');
                    } else if ($request->sorting_value == 2) {
                        $query->orderBy('date', 'desc');
                    } else if ($request->sorting_value == 3) {
                        $query->orderBy('total', 'asc');
                    } else if ($request->sorting_value == 4) {
                        $query->orderBy('total', 'desc');
                    } else {
                        $query->orderBy('created_at', 'desc');
                    }
                } else {
                    $query->orderBy('created_at', 'desc');
                }
                if (isset($request->search_order_supplier)) {
                    if ($request->search_order_supplier == 0) {
                        $query->where('supplier_id', null);
                    } else {
                        $query->where('supplier_id', $request->search_order_supplier);
                    }
                }
                if (isset($request->search_order_cashier)) {
                    $query->where('created_by', $request->search_order_cashier);
                }
                if (isset($request->search_from_date)) {
                    $query->whereDate('date', '>=', $request->search_from_date);
                }
                if (isset($request->search_to_date)) {
                    $query->whereDate('date', '<=', $request->search_to_date);
                }
                if (isset($request->select_convert_status)) {
                    if ($request->select_convert_status == 1) {
                        $query->where('status', 1);
                    } else if ($request->select_convert_status == 2) {
                        $query->where('status', 0);
                    }
                }
                if ($request->currency > 0) {
                    $query->where('currency_id', $request->currency);
                }
                if (isset($request->header_fields)) {
                    $header_fields = $request->header_fields;
                    $query->where(function ($query) use ($header_fields) {
                        $query->where('ref_no', 'like', "%{$header_fields}%")
                            ->orWhereHas('headerParameters', function ($query) use ($header_fields) {
                                $query->where('description', 'like', "%{$header_fields}%");
                            });
                    });
                }
                $payload = QueryBuilder::for($query)
                    ->allowedSorts(['code'])
                    ->allowedFilters(
                        AllowedFilter::callback('search', function ($query, $value) {
                            $query->Where('code', 'like', "%{$value}%");
                        })
                    )
                    ->paginate(request('per_page', config('basic.pagination_per_page')));
                return DataResource::collection($payload);
            }
        }
    }

    /**
     * deletedAll
     *
     * @param  mixed $request
     * @return void
     */
    public function deletedAll(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $query = PurchaseOrder::query()->onlyTrashed();
            if (isset($request->sorting_value)) {
                if ($request->sorting_value == 1) {
                    $query->orderBy('code', 'desc');
                } else if ($request->sorting_value == 2) {
                    $query->orderBy('date', 'desc');
                } else if ($request->sorting_value == 3) {
                    $query->orderBy('total', 'asc');
                } else if ($request->sorting_value == 4) {
                    $query->orderBy('total', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }
            if (isset($request->search_order_supplier)) {
                if ($request->search_order_supplier == 0) {
                    $query->where('supplier_id', null);
                } else {
                    $query->where('supplier_id', $request->search_order_supplier);
                }
            }
            if (isset($request->search_order_cashier)) {
                $query->where('created_by', $request->search_order_cashier);
            }
            if (isset($request->search_from_date)) {
                $query->whereDate('date', '>=', $request->search_from_date);
            }
            if (isset($request->search_to_date)) {
                $query->whereDate('date', '<=', $request->search_to_date);
            }
            if ($request->currency > 0) {
                $query->where('currency_id', $request->currency);
            }
            if (isset($request->header_fields)) {
                $header_fields = $request->header_fields;
                $query->where(function ($query) use ($header_fields) {
                    $query->where('ref_no', 'like', "%{$header_fields}%")
                        ->orWhereHas('headerParameters', function ($query) use ($header_fields) {
                            $query->where('description', 'like', "%{$header_fields}%");
                        });
                });
            }
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['code'])
                ->allowedFilters(
                    AllowedFilter::callback('search', function ($query, $value) {
                        $query->Where('code', 'like', "%{$value}%");
                    })
                )
                ->paginate(request('per_page', config('basic.pagination_per_page')));
            return DataResource::collection($payload);
        }
    }

    /**
     * getPurchaseOrder
     *
     * @return void
     */
    public function getPurchaseOrder()
    {
        return PurchaseOrderFacade::getOrCreate();
    }


    /**
     * getRelatedPurchaseOrder
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function getRelatedPurchaseOrder($purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::getRelatedPurchaseOrder($purchase_order_id);
        }
    }

    /**
     * storeSupplier
     *
     * @param  mixed $supplier_id
     * @return void
     */
    public function storeSupplier($supplier_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::storeSupplier($purchase_order->id, $supplier_id);
        }
    }


    /**
     * getPurchaseOrderSupplier
     *
     * @return void
     */
    public function getPurchaseOrderSupplier()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::getPurchaseOrderSupplier($purchase_order->id);
        }
    }

    /**
     * storeCurrency
     *
     * @param  mixed $currency_id
     * @return void
     */
    public function storeCurrency($currency_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::storeCurrency($purchase_order->id, $currency_id);
        }
    }

    /**
     * storeRef
     *
     * @param  mixed $request
     * @return void
     */
    public function storeRef(RefRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::storeRef($request->all(), $purchase_order->id);
        }
    }

    /**
     * editRef
     *
     * @param  mixed $request
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function editRef(Request $request, $purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::storeRef($request->all(), $purchase_order_id);
        }
    }

    /**
     * storeNote
     *
     * @param  mixed $request
     * @return void
     */
    public function storeNote(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::storeNote($purchase_order->id, $request->all());
        }
    }

    /**
     * editNote
     *
     * @param  mixed $purchase_order_id
     * @param  mixed $request
     * @return void
     */
    public function editNote(int $purchase_order_id, Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::storeNote($purchase_order_id, $request->all());
        }
    }

    /**
     * getEditPurchaseOrderSupplier
     *
     * @param  mixed $request
     * @return void
     */
    public function getEditPurchaseOrderSupplier(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::storeSupplier($request['purchase_order_id'], $request['supplier_id']);
        }
    }

    /**
     * editCurrency
     *
     * @param  mixed $request
     * @return void
     */
    public function editCurrency(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::storeCurrency($request['purchase_order_id'], $request['currency_id']);
        }
    }

    /**
     * updateQty
     *
     * @param  mixed $request
     * @return void
     */
    public function updateQty(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $new_price = $request->quantity * $request->cost;
            return number_format($new_price, 2);
        }
    }


    /**
     * storePODate
     *
     * @param  mixed $request
     * @return void
     */
    public function storePODate(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::storePODate($purchase_order->id, $request->all());
        }
    }


    public function editPODate(Request $request, $purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::storePODate($purchase_order_id, $request->all());
        }
    }

    /**
     * addPurchaseOrderProduct
     *
     * @param  mixed $request
     * @return void
     */
    public function addPurchaseOrderProduct(CreatePOItemRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            $product = ProductFacade::getById($request['product_id']);
            return PurchaseOrderFacade::selectPurchaseOrderProduct($product, $purchase_order->id, $request->all());
        }
    }

    /**
     * addEditPurchaseOrderProduct
     *
     * @param  mixed $request
     * @return void
     */
    public function addEditPurchaseOrderProduct(CreatePOItemRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $product = ProductFacade::getById($request['product_id']);
            return PurchaseOrderFacade::selectPurchaseOrderProduct($product, $request['purchase_order_id'], $request->all());
        }
    }

    /**
     * getPurchaseOrderProduct
     *
     * @return void
     */
    public function getPurchaseOrderProduct()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::getPurchaseOrderProduct($purchase_order);
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
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::getPurchaseOrderItems($purchase_order_id);
        }
    }

    /**
     * getOrderItem
     *
     * @param  mixed $id
     * @return void
     */
    public function getOrderItem(int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::getOrderItem($id, $purchase_order->id);
        }
    }

    /**
     * getPurchaseOrderItem
     *
     * @param  mixed $request
     * @return void
     */
    public function getPurchaseOrderItem(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::getOrderItem($request['id'], $request['purchase_order_id']);
        }
    }

    /**
     * updatePurchaseOrderProductQty
     *
     * @param  mixed $request
     * @param  mixed $product_id
     * @return void
     */
    public function updatePurchaseOrderProductQty(UpdatePOItemQtyRequest $request, int $product_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            $product = ProductFacade::getById($product_id);
            return PurchaseOrderFacade::updateQty($request, $product, $purchase_order->id);
        }
    }

    /**
     * updatePurchaseOrderProduct
     *
     * @param  mixed $request
     * @param  mixed $product_id
     * @return void
     */
    public function updatePurchaseOrderProduct(UpdatePOItemQtyRequest $request, int $order_item_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {

            if ($order_item_id != null) {
                return PurchaseOrderFacade::updateQty($request, $order_item_id, $request['purchase_order_id']);
            } else {
                return 'Product not found';
            }
        }
    }

    /**
     * removeItem
     *
     * @param  mixed $product_id
     * @return void
     */
    public function removeItem(int $product_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::removeItem($product_id, $purchase_order->id);
        }
    }

    /**
     * getTotals
     *
     * @return void
     */
    public function getTotals()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::getTotals($purchase_order->id);
        }
    }

    /**
     * getPurchaseOrderTotals
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function getPurchaseOrderTotals($purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::getTotals($purchase_order_id);
        }
    }

    /**
     * changePurchaseOrderSupplierDetails
     *
     * @param  mixed $request
     * @return void
     */
    public function changePurchaseOrderSupplierDetails(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrderFacade::getOrCreate();
            return PurchaseOrderFacade::editPurchaseOrderSupplier($request->all(), $purchase_order->id);
        }
    }

    /**
     * editPurchaseOrderSupplier
     *
     * @param  mixed $request
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function editPurchaseOrderSupplier(Request $request, $purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::editPurchaseOrderSupplier($request->all(), $purchase_order_id);
        }
    }

    /**
     * removeSupplier
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function removeSupplier($purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::removeSupplierId($purchase_order_id);
        }
    }


    /**
     * editPurchaseOrder
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function editPurchaseOrder(int $purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order = PurchaseOrder::where('id', $purchase_order_id)->withTrashed()->first();
            return $purchase_order;
        }
    }

    /**
     * loadPurchaseOrder
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function loadPurchaseOrder(int $purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            // $response = UserFacade::retrieveHost();
            $response['purchase_order_id'] = $purchase_order_id;
            return Inertia::render('PurchaseOrder/edit', $response);
        }
    }


    /**
     * getForDelete
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function getForDelete($purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::getForDelete($purchase_order_id);
        }
    }

    /**
     * delete
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function delete($purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::delete($purchase_order_id);
        }
    }

    /**
     * restorePurchaseOrder
     *
     * @param  mixed $purchase_order_id
     * @return void
     */
    public function restorePurchaseOrder($purchase_order_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::restorePurchaseOrder($purchase_order_id);
        }
    }

    /**
     * removeSelectedProduct
     *
     * @param  mixed $request
     * @return void
     */
    public function removeSelectedProduct(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::removeItem($request['id'], $request['purchase_order_id']);
        }
    }

    public function print(int $purchase_order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $response['purchase_order'] = PurchaseOrderFacade::get($purchase_order_id);
            $response['created_at'] = $response['purchase_order']['created_at'];
            $response['print_type'] = "po";
            $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();

            $response['custom_fields'] = PurchaseOrderFacade::parametersGetForPrint($purchase_order_id);
            $response['footer_fields'] = PurchaseOrderFacade::footerParametersGetForPrint($purchase_order_id);

            $pdf = PDF::loadView('print.pages.PurchaseOrder.purchase-order', $response)->setPaper('a4');
            return $pdf->stream("Payment.pdf", array("Attachment" => false));
        }
    }


    // Footer parameters
    /**
     * StoreFooterParameter
     * store footer parameter
     *
     * @param CreateFooterParameterRequest $request
     * @param $purchase_order_id
     *
     * @return void
     */
    public function storeFooterParameter(CreateFooterParameterRequest $request, $purchase_order_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order_item_footer_parameters = PurchaseOrderItemFooterParameter::where('purchase_order_id', $purchase_order_id)->where('name', $request['title'])->first();
            if ($purchase_order_item_footer_parameters) {
                $errorMessage = 'The custom title has already been taken.';
                return response()->json([
                    'errors' => [
                        'title' => [$errorMessage]
                    ],
                    'message' => $errorMessage
                ], 402);
            }
            if ($purchase_order_id == null) {
                $purchase_order = PurchaseOrderFacade::getOrCreate();
                $purchase_order_id = $purchase_order->id;
            }
            return PurchaseOrderFacade::storeFooterParameter($purchase_order_id, $request->all());
        }
    }

    /**
     * EditFooterParameter
     *
     * @param $id
     *
     * @return void
     */
    public function editFooterParameter($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::editFooterParameter($id);
        }
    }

    /**
     * GetFooterParameters
     *
     * @param $purchase_order_id
     *
     * @return void
     */
    public function getFooterParameters($purchase_order_id = null)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            if ($purchase_order_id == null) {
                $purchase_order = PurchaseOrderFacade::getOrCreate();
                $purchase_order_id = $purchase_order->id;
            }
            return PurchaseOrderFacade::getFooterParameters($purchase_order_id);
        }
    }

    /**
     * SetFooterDescription
     *
     * @param Request $request
     *
     * @return void
     */
    public function setFooterDescription(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::setFooterDescription($request->all());
        }
    }

    /**
     * UpdateFooterParameter
     *
     * @param Request $request
     *
     * @return void
     */
    public function updateFooterParameter(UpdateFooterParameterRequest $request, $purchase_order_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order_item_footer_parameters = PurchaseOrderItemFooterParameter::where('purchase_order_id', $purchase_order_id)->where('name', $request['title'])->first();
            if ($purchase_order_item_footer_parameters) {
                if ($purchase_order_item_footer_parameters->id != $request['id']) {
                    $errorMessage = 'The custom title has already been taken.';
                    return response()->json([
                        'errors' => [
                            'title' => [$errorMessage]
                        ],
                        'message' => $errorMessage
                    ], 402);
                }
            }
            return PurchaseOrderFacade::updateFooterParameter($request->all());
        }
    }

    /**
     * DeleteFooterParameter
     *
     * @param $field_id
     *
     * @return void
     */
    public function deleteFooterParameter($field_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::deleteFooterParameter($field_id);
        }
    }

    // Header Parameters
    /**
     * StoreParameter
     *
     * @param Request $request
     * @param $purchase_order_id
     *
     * @return void
     */
    public function storeParameter(CreateParameterRequest $request, $purchase_order_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order_item_parameters = PurchaseOrderItemParameter::where('purchase_order_id', $purchase_order_id)->where('name', $request['title'])->first();
            if ($purchase_order_item_parameters) {
                $errorMessage = 'The custom title has already been taken.';
                return response()->json([
                    'errors' => [
                        'title' => [$errorMessage]
                    ],
                    'message' => $errorMessage
                ], 402);
            }
            if ($purchase_order_id == null) {
                $purchase_order = PurchaseOrderFacade::getOrCreate();
                $purchase_order_id = $purchase_order->id;
            }
            return PurchaseOrderFacade::storeParameter($purchase_order_id, $request->all());
        }
    }

    /**
     * GetParameters
     *
     * @param $purchase_order_id
     *
     * @return void
     */
    public function getParameters($purchase_order_id = null)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            if ($purchase_order_id == null) {
                $purchase_order = PurchaseOrderFacade::getOrCreate();
                $purchase_order_id = $purchase_order->id;
            }
            return PurchaseOrderFacade::getParameters($purchase_order_id);
        }
    }

    /**
     * SetDescription
     *
     * @param CreateParameterDescriptionRequest $request
     *
     * @return void
     */
    public function setDescription(CreateParameterDescriptionRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::setDescription($request->all());
        }
    }

    /**
     * EditParameter
     *
     * @param $id
     *
     * @return void
     */
    public function editParameter($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::editParameter($id);
        }
    }

    /**
     * UpdateParameter
     *
     * @param Request $request
     *
     * @return void
     */
    public function updateParameter(UpdateParameterRequest $request, $purchase_order_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $purchase_order_item_parameters = PurchaseOrderItemParameter::where('purchase_order_id', $purchase_order_id)->where('name', $request['title'])->first();
            if ($purchase_order_item_parameters) {
                if ($purchase_order_item_parameters->id != $request['id']) {
                    $errorMessage = 'The custom title has already been taken.';
                    return response()->json([
                        'errors' => [
                            'title' => [$errorMessage]
                        ],
                        'message' => $errorMessage
                    ], 402);
                }
            }
            return PurchaseOrderFacade::updateParameter($request->all());
        }
    }

    /**
     * DeleteParameter
     *
     * @param $field_id
     *
     * @return void
     */
    public function deleteParameter($field_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::deleteParameter($field_id);
        }
    }


    /**
     * SendSupplierPurchaseOrderEmail
     *
     * @param $quotation_id
     * @param SendSupplierPurchaseOrderEmailRequest $request
     *
     * @return void
     */
    public function sendSupplierPurchaseOrderEmail($quotation_id, SendSupplierPurchaseOrderEmailRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PurchaseOrderFacade::sendSupplierPurchaseOrderEmail($quotation_id, $request->all());
        }
    }

    /**
     * CreatePurchaseOrderLink
     *
     * @return void
     */
    public function createPurchaseOrderLink($purchase_order_id)
    {
        return PurchaseOrderFacade::createPurchaseOrderLink($purchase_order_id);
    }


    /**
     * ViewPurchaseOrderPage
     *
     * @param string $purchase_order_key
     *
     * @return void
     */
    public function viewPurchaseOrderPage(string $purchase_order_key)
    {
        $purchase_order_id = PurchaseOrderFacade::getPublicPurchaseOrder($purchase_order_key);
        $response['purchase_order'] = PurchaseOrderFacade::get($purchase_order_id);
        $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();
        $response['created_at'] = $response['purchase_order']['created_at'];
        $response['print_type'] = "po";
        $response['custom_fields'] = PurchaseOrderFacade::parametersGetForPrint($purchase_order_id);
        $response['footer_fields'] = PurchaseOrderFacade::footerParametersGetForPrint($purchase_order_id);
        $pdf = PDF::loadView('print.pages.PurchaseOrder.purchase-order', $response)->setPaper('a4');

        $pdfPath = 'purchase_order/' . $purchase_order_key . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
        $pdfUrl = Storage::url($pdfPath);

        $businessDetails = $response['config'];
        return Inertia::render('PublicArea/PurchaseOrder/view', [
            'pdfUrl' => $pdfUrl,
            'businessDetails' => $businessDetails,
        ]);
    }
}
