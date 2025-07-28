<?php

namespace App\Http\Controllers;

use App\Exports\Reports\InvoiceReportExport;
use App\Http\Requests\Invoice\CreateCodeRequest;
use App\Http\Requests\Invoice\CreateFooterParameterRequest;
use App\Http\Requests\Invoice\CreateParameterDescriptionRequest;
use App\Http\Requests\Invoice\CreateParameterRequest;
use App\Http\Requests\Invoice\SendCustomerInvoiceEmailRequest;
use App\Http\Requests\Invoice\UpdateFooterParameterRequest;
use App\Http\Requests\Invoice\UpdateParameterRequest;
use App\Http\Requests\PosOrderItem\CreateInvoiceItemRequest;
use App\Http\Requests\PosOrderItem\UpdateQtyRequest;
use App\Http\Resources\DataResource;
use App\Models\BusinessDetail;
use App\Models\InvoiceItemFooterParameter;
use App\Models\InvoiceItemParameter;
use App\Models\User;
use App\Models\PosOrder;
use domain\Facades\BillPaymentFacade\BillPaymentFacade;
use domain\Facades\InvoiceFacade\InvoiceFacade;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\PosReceiptFacade\PosReceiptFacade;
use domain\Facades\ProductFacade\ProductFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends ParentController
{

    public function viewAllInvoices()
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        // $pending_invoice = PosOrder::where('created_by', Auth::id())->where('status', 0)->where('type', 1)->first();
        // if ($pending_invoice) {
        //     if ($pending_invoice->total > 0 && $pending_invoice->customer_paid == 0) {
        //         $pending_invoice->status = 1;
        //         $pending_invoice->save();
        //     }
        //     if ($pending_invoice->total > 0 && $pending_invoice->customer_paid > 0) {
        //         $pending_invoice->status = 1;
        //         $pending_invoice->save();
        //     }
        // }
        // $response = UserFacade::retrieveHost();
        return Inertia::render('Invoice/view');
        // }
    }

    public function deletedList()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return Inertia::render('Invoice/deleted');
        }
    }

    public function index()
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        $invoice = InvoiceFacade::getOrCreateNew();
        return redirect()->route('invoice.process', $invoice->id);
        // }
    }

    public function process($invoice_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = InvoiceFacade::get($invoice_id);
            if ($invoice->created_by != Auth::id()) {
                $response['alert-danger'] = 'Invoice Can\'t be processed.';
                return redirect()->route('cart')->with($response);
            } else {
                $response['invoice_id'] = $invoice_id;
                return Inertia::render('Invoice/edit', $response);
            }
        }
    }

    public function all(Request $request)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        $query = PosOrder::query()->where('type', 1);
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
                $query->orderByRaw('date DESC, created_at DESC');
            }
        } else {
            $query->orderByRaw('date DESC, created_at DESC');
        }
        if (isset($request->search_order_customer)) {
            if ($request->search_order_customer == 0) {
                $query->where('customer_id', 0);
            } else {
                $query->where('customer_id', $request->search_order_customer);
            }
        }
        if (isset($request->search_order_cashier)) {
            $query->where('created_by', $request->search_order_cashier);
        }
        if (isset($request->search_order_from_date)) {
            $query->whereDate('date', '>=', $request->search_order_from_date);
        }
        if (isset($request->search_order_to_date)) {
            $query->whereDate('date', '<=', $request->search_order_to_date);
        }
        if (isset($request->search_order_status)) {
            if ($request->search_order_status == 1) {
                $query->where('customer_paid', '>', 0)
                    ->whereRaw('total - customer_paid <= 0');
            }
            if ($request->search_order_status == 2) {
                $query->where('customer_paid', '>', 0)
                    ->whereRaw('total - customer_paid > 0');
            }
            if ($request->search_order_status == 3) {
                $query->where('customer_paid', '==', 0);
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
        // }
    }

    public function deletedAll(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $query = PosOrder::query()->where('status', '>=', 0)->where('status', '<', 4)->where('type', 1)->whereNotIn('status', [3])->onlyTrashed();
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
                    $query->orderByRaw('date DESC, created_at DESC');
                }
            } else {
                $query->orderByRaw('date DESC, created_at DESC');
            }
            if (isset($request->search_order_customer)) {
                if ($request->search_order_customer == 0) {
                    $query->where('customer_id', 0);
                } else {
                    $query->where('customer_id', $request->search_order_customer);
                }
            }
            if (isset($request->search_order_cashier)) {
                $query->where('created_by', $request->search_order_cashier);
            }
            if (isset($request->search_order_from_date)) {
                $query->whereDate('date', '>=', $request->search_order_from_date);
            }
            if (isset($request->search_order_to_date)) {
                $query->whereDate('date', '<=', $request->search_order_to_date);
            }
            if (isset($request->search_order_status)) {
                if ($request->search_order_status == 1) {
                    $query->where('credit_status', 1);
                }
                if ($request->search_order_status == 2) {
                    $query->where('credit_status', 0)->where('customer_paid', '>', 0);
                }
                if ($request->search_order_status == 3) {
                    $query->where('status', 1)->where('customer_paid', 0);
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

    public function getInvoice(int $invoice_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::getInvoice($invoice_id);
        }
    }

    public function getRelatedInvoice($invoice_id)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        return InvoiceFacade::getRelatedInvoice($invoice_id);
        // }
    }

    // Header Parameters
    public function storeParameter(CreateParameterRequest $request, $invoice_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice_item_parameter = InvoiceItemParameter::where('invoice_id', $invoice_id)->where('name', $request['title'])->first();
            if ($invoice_item_parameter) {
                $errorMessage = 'The custom title has already been taken.';
                return response()->json([
                    'errors' => [
                        'title' => [$errorMessage]
                    ],
                    'message' => $errorMessage
                ], 402);
            }
            if ($invoice_id == null) {
                $invoice = InvoiceFacade::getOrCreate();
                $invoice_id = $invoice->id;
            }
            return InvoiceFacade::storeParameter($invoice_id, $request->all());
        }
    }

    public function updateParameter(UpdateParameterRequest $request, $invoice_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice_item_parameter = InvoiceItemParameter::where('invoice_id', $invoice_id)->where('name', $request['title'])->first();
            if ($invoice_item_parameter) {
                if ($invoice_item_parameter->id != $request['id']) {
                    $errorMessage = 'The custom title has already been taken.';
                    return response()->json([
                        'errors' => [
                            'title' => [$errorMessage]
                        ],
                        'message' => $errorMessage
                    ], 402);
                }
            }
            return InvoiceFacade::updateParameter($request->all());
        }
    }

    public function getParameters($invoice_id = null)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        if ($invoice_id == null) {
            $invoice = InvoiceFacade::getOrCreate();
            $invoice_id = $invoice->id;
        }
        return InvoiceFacade::getParameters($invoice_id);
        // }
    }

    public function setDescription(CreateParameterDescriptionRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::setDescription($request->all());
        }
    }

    public function editParameter($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::editParameter($id);
        }
    }

    public function deleteParameter($field_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::deleteParameter($field_id);
        }
    }
    // End Header Parameters

    // Footer Parameters
    public function storeFooterParameter(CreateFooterParameterRequest $request, $invoice_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice_item_footer_parameter = InvoiceItemFooterParameter::where('invoice_id', $invoice_id)->where('name', $request['title'])->first();
            if ($invoice_item_footer_parameter) {
                $errorMessage = 'The custom title has already been taken.';
                return response()->json([
                    'errors' => [
                        'title' => [$errorMessage]
                    ],
                    'message' => $errorMessage
                ], 402);
            }
            if ($invoice_id == null) {
                $invoice = InvoiceFacade::getOrCreate();
                $invoice_id = $invoice->id;
            }
            return InvoiceFacade::storeFooterParameter($invoice_id, $request->all());
        }
    }

    public function updateFooterParameter(UpdateFooterParameterRequest $request, $invoice_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice_item_footer_parameter = InvoiceItemFooterParameter::where('invoice_id', $invoice_id)->where('name', $request['title'])->first();
            if ($invoice_item_footer_parameter) {
                if ($invoice_item_footer_parameter->id != $request['id']) {
                    $errorMessage = 'The custom title has already been taken.';
                    return response()->json([
                        'errors' => [
                            'title' => [$errorMessage]
                        ],
                        'message' => $errorMessage
                    ], 402);
                }
            }
            return InvoiceFacade::updateFooterParameter($request->all());
        }
    }

    public function getFooterParameters($invoice_id = null)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        if ($invoice_id == null) {
            $invoice = InvoiceFacade::getOrCreate();
            $invoice_id = $invoice->id;
        }
        return InvoiceFacade::getFooterParameters($invoice_id);
        // }
    }

    public function setFooterDescription(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::setFooterDescription($request->all());
        }
    }

    public function editFooterParameter($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::editFooterParameter($id);
        }
    }

    public function deleteFooterParameter($field_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::deleteFooterParameter($field_id);
        }
    }
    // End Footer Parameters

    public function storeCustomer($customer_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = InvoiceFacade::getOrCreate();
            return InvoiceFacade::storeCustomer($invoice->id, $customer_id);
        }
    }

    public function storeCurrency($currency_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = InvoiceFacade::getOrCreate();
            return InvoiceFacade::storeCurrency($invoice->id, $currency_id);
        }
    }

    public function storeInvoiceRef(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = InvoiceFacade::getOrCreate();
            return InvoiceFacade::storeInvoiceRef($request->all(), $invoice->id);
        }
    }

    public function editInvoiceRef($invoice_id, Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::storeInvoiceRef($request->all(), $invoice_id);
        }
    }

    public function storeDate(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = InvoiceFacade::getOrCreate();
            return InvoiceFacade::storeDate($invoice->id, $request->all());
        }
    }

    public function storeDueDate(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = InvoiceFacade::getOrCreate();
            return InvoiceFacade::storeDueDate($invoice->id, $request->all());
        }
    }

    public function storeNote(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = InvoiceFacade::getOrCreate();
            return InvoiceFacade::storeNote($invoice->id, $request->all());
        }
    }

    public function storeCode(CreateCodeRequest $request, $invoice_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            if ($invoice_id == null) {
                $invoice = InvoiceFacade::getOrCreate();
                $invoice_id = $invoice->id;
            }
            return InvoiceFacade::storeCode($request->all(), $invoice_id);
        }
    }

    public function editDate(int $invoice_id, Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::storeDate($invoice_id, $request->all());
        }
    }

    public function editDueDate(int $invoice_id, Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::storeDueDate($invoice_id, $request->all());
        }
    }

    public function editNote(int $invoice_id, Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::storeNote($invoice_id, $request->all());
        }
    }

    public function editCustomer(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::storeCustomer($request['invoice_id'], $request['customer_id']);
        }
    }

    public function editCurrency(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::storeCurrency($request['invoice_id'], $request['currency_id']);
        }
    }

    public function updateQty(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $new_price = $request->quantity * $request->selling_price;
            return number_format($new_price, 2);
        }
    }

    public function addInvoiceProduct(CreateInvoiceItemRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $product = ProductFacade::getById($request['product_id']);
            return PosOrderFacade::selectInvoiceProduct($product, $request['invoice_id'], $request->all());
        }
    }

    public function addEditInvoiceProduct(CreateInvoiceItemRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $product = ProductFacade::getById($request['product_id']);
            return PosOrderFacade::selectInvoiceProduct($product, $request['invoice_id'], $request->all());
        }
    }

    public function getOrderProduct()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $order = InvoiceFacade::getOrCreate();
            return PosOrderFacade::getOrderProduct($order);
        }
    }

    public function getInvoiceProduct($invoice_id)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        return PosOrderFacade::getInvoiceProduct($invoice_id);
        // }
    }

    public function getOrderItem(int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $order = InvoiceFacade::getOrCreate();
            return ProductFacade::getOrderItem($id, $order->id);
        }
    }

    public function getInvoiceItem(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ProductFacade::getOrderItem($request['id'], $request['invoice_id']);
        }
    }

    public function updateInvoiceProductQty(UpdateQtyRequest $request, int $product_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $order = InvoiceFacade::getOrCreate();
            $product = ProductFacade::getById($product_id);
            return PosOrderFacade::updateQty($request, $product, $order->id);
        }
    }

    public function updateInvoiceProduct(UpdateQtyRequest $request, int $order_item_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            if ($order_item_id != null) {
                return PosOrderFacade::updateQty($request, $order_item_id, $request['invoice_id']);
            } else {
                return 'Product not found';
            }
        }
    }

    public function removeItem(int $product_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $order = InvoiceFacade::getOrCreate();
            return PosOrderFacade::removeItem($product_id, $order->id);
        }
    }

    public function removeSelectedProduct(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PosOrderFacade::removeItem($request['id'], $request['invoice_id']);
        }
    }

    public function getTotals()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $order = InvoiceFacade::getOrCreate();
            return PosOrderFacade::getTotals($order->id);
        }
    }

    public function getNewTotals($invoice_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PosOrderFacade::getTotals($invoice_id);
        }
    }

    public function getInvoiceTotals($invoice_id)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        return PosOrderFacade::getTotals($invoice_id);
        // }
    }

    public function checkInvoiceStatus($invoice_id)
    {
        return PosOrderFacade::checkInvoiceStatus($invoice_id);
    }

    public function getLoyaltyCustomer()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = InvoiceFacade::getOrCreate();
            return InvoiceFacade::getLoyaltyCustomer($invoice->id);
        }
    }

    public function getInvoiceCustomer($invoice_id)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        return InvoiceFacade::getLoyaltyCustomer($invoice_id);
        // }
    }

    public function changeInvoiceCustomer(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = InvoiceFacade::getOrCreate();
            return InvoiceFacade::changeInvoiceCustomer($request->all(), $invoice->id);
        }
    }

    public function editInvoiceCustomer($invoice_id, Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::changeInvoiceCustomer($request->all(), $invoice_id);
        }
    }

    public function holdCart()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $order = InvoiceFacade::getOrCreate();
            return PosOrderFacade::holdCart($order->id);
        }
    }

    public function editInvoice(int $invoice_id)
    {
        $invoice = PosOrder::where('id', $invoice_id)->withTrashed()->first();
        if ($invoice->type == 1) {
            return $invoice;
        }
    }
    public function loadInvoice(int $invoice_id)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
        // $response = UserFacade::retrieveHost();
        $response['invoice_id'] = $invoice_id;
        return Inertia::render('Invoice/edit', $response);
        // }
    }
    public function editBill(int $bill_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PosReceiptFacade::editBill($bill_id);
        }
    }

    public function getForDelete($invoice_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::getForDelete($invoice_id);
        }
    }

    public function delete($invoice_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::delete($invoice_id);
        }
    }

    public function restoreInvoice($invoice_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::restoreInvoice($invoice_id);
        }
    }

    /**
     * SendCustomerInvoiceEmail
     *
     * @param $invoice_id
     * @param SendCustomerInvoiceEmailRequest $request
     *
     * @return void
     */
    public function sendCustomerInvoiceEmail($invoice_id, SendCustomerInvoiceEmailRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['CASHIER'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return InvoiceFacade::sendCustomerInvoiceEmail($invoice_id, $request->all());
        }
    }

    public function createInvoiceLink(int $invoice_id)
    {
        return InvoiceFacade::createInvoiceLink($invoice_id);
    }

    public function viewInvoicePage(string $invoice_key)
    {
        $invoice_id = InvoiceFacade::getPublicInvoice($invoice_key);
        $order = PosOrder::find($invoice_id);
        $response['order'] = PosOrderFacade::get($invoice_id);
        $response['bill'] = BillPaymentFacade::get($invoice_id);
        $response['config'] = BusinessDetail::latest()->first();

        if ($order->type == 0) {
            $pdf = PDF::loadView('print.pages.Bill.payment', $response)->setPaper([0, 0, 300, 700], 'portrait');
        } else {
            $response['custom_fields'] = InvoiceFacade::parametersGetForPrint($invoice_id);
            $response['footer_fields'] = InvoiceFacade::footerParametersGetForPrint($invoice_id);
            $pdf = PDF::loadView('print.pages.Invoice.invoice', $response)->setPaper('a4');
        }

        $pdfPath = 'invoices/' . $invoice_key . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
        $pdfUrl = Storage::url($pdfPath);

        $businessDetails = $response['config'];
        return Inertia::render('PublicArea/Invoice/view', [
            'pdfUrl' => $pdfUrl,
            'businessDetails' => $businessDetails,
        ]);
    }

    public function export(Request $request)
    {
        $invoice_no = $request->input('invoice_no');
        $search_order_customer = $request->input('search_order_customer');
        $search_order_cashier = $request->input('search_order_cashier');
        $search_order_from_date = $request->input('search_order_from_date');
        $search_order_to_date = $request->input('search_order_to_date');
        $search_order_status = $request->input('search_order_status');
        $currency = $request->input('currency');
        $header_fields = $request->input('header_fields');

        $invoices = $this->buildInvoiceQuery($request);


        if ($currency['id'] != 0) {
            $totals = InvoiceFacade::calculateTotals($invoices);
        } else {
            $totals = null;
        }

        $data = [
            'invoice_no' => $invoice_no,
            'search_order_customer' => $search_order_customer,
            'search_order_cashier' => $search_order_cashier,
            'search_order_from_date' => $search_order_from_date,
            'search_order_to_date' => $search_order_to_date,
            'invoices' => $invoices,
            'search_order_status' => $search_order_status,
            'header_fields' => $header_fields,
            'totals' => $totals,
            'currency' => $currency,
        ];

        $fileName = 'InvoiceReport-' . date('Y-m-d') . '-' . time() . '.xlsx';
        $filePath = 'exports/Reports/' . $fileName;
        $invoice_export = new InvoiceReportExport();
        Excel::store($invoice_export->export($data), $filePath, 'public');

        $path = asset('storage/' . $filePath);

        return response()->json(['path' => $path]);
    }

    private function buildInvoiceQuery(Request $request)
    {
        $query = PosOrder::query()->where('type', 1);
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
                $query->orderByRaw('date DESC, created_at DESC');
            }
        } else {
            $query->orderByRaw('date DESC, created_at DESC');
        }
        if (isset($request->search_order_customer)) {
            if ($request->search_order_customer == 0) {
                $query->where('customer_id', 0);
            } else {
                $query->where('customer_id', $request->search_order_customer);
            }
        }
        if (isset($request->search_order_cashier)) {
            $query->where('created_by', $request->search_order_cashier);
        }
        if (isset($request->search_order_from_date)) {
            $query->whereDate('date', '>=', $request->search_order_from_date);
        }
        if (isset($request->search_order_to_date)) {
            $query->whereDate('date', '<=', $request->search_order_to_date);
        }
        if (isset($request->search_order_status)) {
            if ($request->search_order_status == 1) {
                $query->where('credit_status', 1);
            }
            if ($request->search_order_status == 2) {
                $query->where('credit_status', 0)->where('customer_paid', '>', 0);
            }
            if ($request->search_order_status == 3) {
                $query->where('status', 1)->where('customer_paid', 0);
            }
        }
        if ($request->currency['id'] > 0) {
            $query->where('currency_id', $request->currency);
        }
        if ($request->invoice_no) {
            $query->where('code', 'like', "%{$request->invoice_no}%");
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

        $payload = $query->get();
        return DataResource::collection($payload);
    }
}
