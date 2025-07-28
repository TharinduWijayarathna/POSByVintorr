<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosOrderItem\CreateInvoiceItemRequest;
use App\Http\Requests\PosOrderItem\UpdateQtyRequest;
use App\Http\Requests\Quotation\CreateFooterParameterRequest;
use App\Http\Requests\Quotation\CreateParameterDescriptionRequest;
use App\Http\Requests\Quotation\CreateParameterRequest;
use App\Http\Requests\Quotation\SendCustomerQuotationEmailRequest;
use App\Http\Requests\Quotation\UpdateFooterParameterRequest;
use App\Http\Requests\Quotation\UpdateParameterRequest;
use App\Http\Resources\DataResource;
use App\Models\BusinessDetail;
use App\Models\Quotation;
use App\Models\QuotationItemFooterParameter;
use App\Models\QuotationItemParameter;
use App\Models\User;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\PosReceiptFacade\PosReceiptFacade;
use domain\Facades\ProductFacade\ProductFacade;
use domain\Facades\QuotationFacade\QuotationFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use PDF;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QuotationController extends ParentController
{
    public function viewAllInvoices()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Quotation/view');
        }
    }

    public function deletedList()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return Inertia::render('Quotation/deleted');
        }
    }

    public function index()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreateNew();

            return redirect()->route('quotation.process', $quotation->id);
        }
    }

    public function process($quotation_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = QuotationFacade::get($quotation_id);
            if ($invoice->status == 1 || $invoice->created_by != Auth::id()) {
                $response['alert-danger'] = 'Invoice Can\'t be processed.';

                return redirect()->route('cart')->with($response);
            } else {
                $response['quotation_id'] = $quotation_id;

                return Inertia::render('Quotation/edit', $response);
            }
        }
    }

    public function all(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $query = Quotation::query();
            if (isset($request->sorting_value)) {
                if ($request->sorting_value == 1) {
                    $query->orderBy('code', 'desc')->orderBy('code', 'desc');
                } elseif ($request->sorting_value == 2) {
                    $query->orderBy('date', 'desc');
                } elseif ($request->sorting_value == 3) {
                    $query->orderBy('total', 'asc');
                } elseif ($request->sorting_value == 4) {
                    $query->orderBy('total', 'desc');
                } else {
                    $query->orderBy('date', 'desc')->orderBy('code', 'desc');
                }
            } else {
                $query->orderBy('date', 'desc')->orderBy('code', 'desc');
            }
            if (isset($request->search_order_customer)) {
                if ($request->search_order_customer == 0) {
                    $query->where('customer_id', null);
                } else {
                    $query->where('customer_id', $request->search_order_customer);
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
                    $query->where('convert_status', 1);
                } elseif ($request->select_convert_status == 2) {
                    $query->where('convert_status', 0);
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

    public function deletedAll(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $query = Quotation::query()->where('status', 1)->onlyTrashed();
            if (isset($request->sorting_value)) {
                if ($request->sorting_value == 1) {
                    $query->orderBy('code', 'desc');
                } elseif ($request->sorting_value == 2) {
                    $query->orderBy('date', 'desc')->orderBy('code', 'desc');
                } elseif ($request->sorting_value == 3) {
                    $query->orderBy('total', 'asc');
                } elseif ($request->sorting_value == 4) {
                    $query->orderBy('total', 'desc');
                } else {
                    $query->orderBy('date', 'desc')->orderBy('code', 'desc');
                }
            } else {
                $query->orderBy('date', 'desc')->orderBy('code', 'desc');
            }
            if (isset($request->search_order_customer)) {
                if ($request->search_order_customer == 0) {
                    $query->where('customer_id', null);
                } else {
                    $query->where('customer_id', $request->search_order_customer);
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

    public function discount(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            QuotationFacade::discount($request->all());
        }
    }

    public function removeDiscount(int $quotation_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            QuotationFacade::removeDiscount($quotation_id);
        }
    }

    public function getInvoice()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::getOrCreate();
        }
    }

    public function getRelatedQuotation($quotation_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return QuotationFacade::getRelatedQuotation($quotation_id);
        }
    }

    public function storeCustomer($customer_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();

            return QuotationFacade::storeCustomer($quotation->id, $customer_id);
        }
    }

    public function storeCurrency($currency_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();

            return QuotationFacade::storeCurrency($quotation->id, $currency_id);
        }
    }

    public function storeRef(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();

            return QuotationFacade::storeRef($request->all(), $quotation->id);
        }
    }

    public function editRef(Request $request, $quotation_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::storeRef($request->all(), $quotation_id);
        }
    }

    public function storeNote(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $invoice = QuotationFacade::getOrCreate();

            return QuotationFacade::storeNote($invoice->id, $request->all());
        }
    }

    public function editNote(int $quotation_id, Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::storeNote($quotation_id, $request->all());
        }
    }

    public function editCustomer(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::storeCustomer($request['quotation_id'], $request['customer_id']);
        }
    }

    public function editCurrency(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::storeCurrency($request['quotation_id'], $request['currency_id']);
        }
    }

    public function updateQty(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $new_price = $request->quantity * $request->selling_price;

            return number_format($new_price, 2);
        }
    }

    public function addQuotationProduct(CreateInvoiceItemRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();
            $product = ProductFacade::getById($request['product_id']);

            return QuotationFacade::selectQuotationProduct($product, $quotation->id, $request->all());
        }
    }

    public function addEditInvoiceProduct(CreateInvoiceItemRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $product = ProductFacade::getById($request['product_id']);

            return QuotationFacade::selectQuotationProduct($product, $request['quotation_id'], $request->all());
        }
    }

    public function getQuotationProduct()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();

            return QuotationFacade::getQuotationProduct($quotation);
        }
    }

    public function getQuotationItems($quotation_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return QuotationFacade::getQuotationItems($quotation_id);
        }
    }

    public function getOrderItem(int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();

            return QuotationFacade::getOrderItem($id, $quotation->id);
        }
    }

    public function getQuotationItem(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::getOrderItem($request['id'], $request['quotation_id']);
        }
    }

    public function updateQuotationProductQty(UpdateQtyRequest $request, int $product_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();
            $product = ProductFacade::getById($product_id);

            return QuotationFacade::updateQty($request, $product, $quotation->id);
        }
    }

    public function updateQuotationProduct(UpdateQtyRequest $request, int $order_item_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {

            if ($order_item_id != null) {
                return QuotationFacade::updateQty($request, $order_item_id, $request['quotation_id']);
            } else {
                return 'Product not found';
            }
        }
    }

    public function removeItem(int $product_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();

            return QuotationFacade::removeItem($product_id, $quotation->id);
        }
    }

    public function getTotals()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();

            return QuotationFacade::getTotals($quotation->id);
        }
    }

    public function getInvoiceTotals($quotation_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return QuotationFacade::getTotals($quotation_id);
        }
    }

    public function getLoyaltyCustomer()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();

            return QuotationFacade::getLoyaltyCustomer($quotation->id);
        }
    }

    public function getEditLoyaltyCustomer($quotation_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return QuotationFacade::getLoyaltyCustomer($quotation_id);
        }
    }

    public function changeQuotationCustomerDetails(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation = QuotationFacade::getOrCreate();

            return QuotationFacade::editQuotationCustomer($request->all(), $quotation->id);
        }
    }

    public function editQuotationCustomer(Request $request, $quotation_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::editQuotationCustomer($request->all(), $quotation_id);
        }
    }

    public function removeCustomer($quotation_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::removeCustomerId($quotation_id);
        }
    }

    public function holdCart()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $order = QuotationFacade::getOrCreate();

            return PosOrderFacade::holdCart($order->id);
        }
    }

    public function editInvoice(int $quotation_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $quotation = Quotation::where('id', $quotation_id)->withTrashed()->first();

            return $quotation;
        }
    }

    public function loadQuotation(int $quotation_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            $response['quotation_id'] = $quotation_id;

            return Inertia::render('Quotation/edit', $response);
        }
    }

    public function editBill(int $bill_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PosReceiptFacade::editBill($bill_id);
        }
    }

    public function getForDelete($quotation_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::getForDelete($quotation_id);
        }
    }

    public function delete($quotation_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::delete($quotation_id);
        }
    }

    public function restoreQuotation($quotation_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::restoreQuotation($quotation_id);
        }
    }

    public function removeSelectedProduct(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::removeItem($request['id'], $request['quotation_id']);
        }
    }

    public function print(int $quotation_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $response['quotation'] = QuotationFacade::get($quotation_id);
            $response['quotation_items'] = QuotationFacade::allItems($quotation_id);
            $response['created_at'] = $response['quotation']['created_at'];
            $response['print_type'] = 'quotation';
            $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();

            $response['custom_fields'] = QuotationFacade::parametersGetForPrint($quotation_id);
            $response['footer_fields'] = QuotationFacade::footerParametersGetForPrint($quotation_id);

            $pdf = PDF::loadView('print.pages.Quotation.quotation', $response)->setPaper('a4');

            return $pdf->stream('Payment.pdf', ['Attachment' => false]);
        }
    }

    public function convertToInvoice($quotation_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::convertToInvoice($quotation_id);
        }
    }

    // Footer parameters
    /**
     * StoreFooterParameter
     * store footer parameter
     *
     *
     * @return void
     */
    public function storeFooterParameter(CreateFooterParameterRequest $request, $quotation_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation_item_footer_parameters = QuotationItemFooterParameter::where('quotation_id', $quotation_id)->where('name', $request['title'])->first();
            if ($quotation_item_footer_parameters) {
                $errorMessage = 'The custom title has already been taken.';

                return response()->json([
                    'errors' => [
                        'title' => [$errorMessage],
                    ],
                    'message' => $errorMessage,
                ], 402);
            }
            if ($quotation_id == null) {
                $quotation = QuotationFacade::getOrCreate();
                $quotation_id = $quotation->id;
            }

            return QuotationFacade::storeFooterParameter($quotation_id, $request->all());
        }
    }

    /**
     * EditFooterParameter
     *
     *
     * @return void
     */
    public function editFooterParameter($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::editFooterParameter($id);
        }
    }

    /**
     * GetFooterParameters
     *
     *
     * @return void
     */
    public function getFooterParameters($quotation_id = null)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            if ($quotation_id == null) {
                $quotation = QuotationFacade::getOrCreate();
                $quotation_id = $quotation->id;
            }

            return QuotationFacade::getFooterParameters($quotation_id);
        }
    }

    /**
     * SetFooterDescription
     *
     *
     * @return void
     */
    public function setFooterDescription(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::setFooterDescription($request->all());
        }
    }

    /**
     * UpdateFooterParameter
     *
     * @param  Request  $request
     * @return void
     */
    public function updateFooterParameter(UpdateFooterParameterRequest $request, $quotation_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation_item_footer_parameters = QuotationItemFooterParameter::where('quotation_id', $quotation_id)->where('name', $request['title'])->first();
            if ($quotation_item_footer_parameters) {
                if ($quotation_item_footer_parameters->id != $request['id']) {
                    $errorMessage = 'The custom title has already been taken.';

                    return response()->json([
                        'errors' => [
                            'title' => [$errorMessage],
                        ],
                        'message' => $errorMessage,
                    ], 402);
                }
            }

            return QuotationFacade::updateFooterParameter($request->all());
        }
    }

    /**
     * DeleteFooterParameter
     *
     *
     * @return void
     */
    public function deleteFooterParameter($field_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::deleteFooterParameter($field_id);
        }
    }

    // Header Parameters
    /**
     * StoreParameter
     *
     * @param  Request  $request
     * @return void
     */
    public function storeParameter(CreateParameterRequest $request, $quotation_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation_item_parameter = QuotationItemParameter::where('quotation_id', $quotation_id)->where('name', $request['title'])->first();
            if ($quotation_item_parameter) {
                $errorMessage = 'The custom title has already been taken.';

                return response()->json([
                    'errors' => [
                        'title' => [$errorMessage],
                    ],
                    'message' => $errorMessage,
                ], 402);
            }
            if ($quotation_id == null) {
                $quotation = QuotationFacade::getOrCreate();
                $quotation_id = $quotation->id;
            }

            return QuotationFacade::storeParameter($quotation_id, $request->all());
        }
    }

    /**
     * GetParameters
     *
     *
     * @return void
     */
    public function getParameters($quotation_id = null)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            if ($quotation_id == null) {
                $quotation = QuotationFacade::getOrCreate();
                $quotation_id = $quotation->id;
            }

            return QuotationFacade::getParameters($quotation_id);
        }
    }

    /**
     * SetDescription
     *
     *
     * @return void
     */
    public function setDescription(CreateParameterDescriptionRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::setDescription($request->all());
        }
    }

    /**
     * EditParameter
     *
     *
     * @return void
     */
    public function editParameter($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::editParameter($id);
        }
    }

    /**
     * UpdateParameter
     *
     * @param  Request  $request
     * @return void
     */
    public function updateParameter(UpdateParameterRequest $request, $quotation_id = null)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $quotation_item_parameter = QuotationItemParameter::where('quotation_id', $quotation_id)->where('name', $request['title'])->first();
            if ($quotation_item_parameter) {
                if ($quotation_item_parameter->id != $request['id']) {
                    $errorMessage = 'The custom title has already been taken.';

                    return response()->json([
                        'errors' => [
                            'title' => [$errorMessage],
                        ],
                        'message' => $errorMessage,
                    ], 402);
                }
            }

            return QuotationFacade::updateParameter($request->all());
        }
    }

    /**
     * DeleteParameter
     *
     *
     * @return void
     */
    public function deleteParameter($field_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::deleteParameter($field_id);
        }
    }

    /**
     * SendCustomerQuotationEmail
     *
     *
     * @return void
     */
    public function sendCustomerQuotationEmail($quotation_id, SendCustomerQuotationEmailRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return QuotationFacade::sendCustomerQuotationEmail($quotation_id, $request->all());
        }
    }

    public function createQuotationLink(int $quotation_id)
    {
        return QuotationFacade::createQuotationLink($quotation_id);
    }

    public function viewQuotationPage(string $quotation_key)
    {
        $quotation_id = QuotationFacade::getPublicQuotation($quotation_key);
        $response['quotation'] = QuotationFacade::get($quotation_id);
        $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();
        $response['custom_fields'] = QuotationFacade::parametersGetForPrint($quotation_id);
        $response['footer_fields'] = QuotationFacade::footerParametersGetForPrint($quotation_id);
        $pdf = PDF::loadView('print.pages.Quotation.quotation', $response)->setPaper('a4');

        $pdfPath = 'quotations/'.$quotation_key.'.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
        $pdfUrl = Storage::url($pdfPath);

        $businessDetails = $response['config'];

        return Inertia::render('PublicArea/Quotation/view', [
            'pdfUrl' => $pdfUrl,
            'businessDetails' => $businessDetails,
        ]);
    }
}
