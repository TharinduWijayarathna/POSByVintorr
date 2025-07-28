<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosReceipt\CreateBillRequest;
use App\Http\Requests\PosReceipt\UpdateBillRequest;
use App\Http\Resources\DataResource;
use App\Models\PosOrder;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\PosReceiptFacade\PosReceiptFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PosReceiptController extends ParentController
{
    public function index()
    {
        return Inertia::render('Receipt/index');
    }

    public function credit()
    {
        // $response = UserFacade::retrieveHost();
        return Inertia::render('Receipt/credit');
    }

    public function all(Request $request)
    {
        $query = PosOrder::query()->where('status', '>', 0)->where('status', '<', 4)->where('type', 0)->whereNotIn('status', [3]);
        if (isset($request->sorting_value)) {
            if ($request->sorting_value == 1) {
                $query->orderBy('code', 'desc');
            } else if ($request->sorting_value == 2) {
                $query->orderBy('date', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
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
        if (isset($request->search_order_from_date)) {
            $query->whereDate('date', '>=', $request->search_order_from_date);
        }
        if (isset($request->search_order_to_date)) {
            $query->whereDate('date', '<=', $request->search_order_to_date);
        }
        if (isset($request->search_order_status)) {
            if ($request->search_order_status > 0) {
                $query->where('status', $request->search_order_status);
            }
        }
        if ($request->currency > 0) {
            $query->where('currency_id', $request->currency);
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

    public function creditAll(Request $request)
    {
        $query = PosOrder::query()->where('status', 1)->where('credit_status', 0)->where('is_return', 0)->where('type', 0)->orderBy('updated_at', 'desc');
        if (isset($request->search_order_customer)) {
            $query->where('customer_id', $request->search_order_customer);
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
        if (isset($request->search_order_type)) {
            if ($request->search_order_type == 0) {
                $query->where(function ($query) {
                    $query->where('status', 1)
                        ->orWhere('status', 2);
                });
            }
            if ($request->search_order_type == 1) {
                $query->where('type', 0);
                $query->where('status', 1);
            }
            if ($request->search_order_type == 2) {
                $query->where('type', 1);
                $query->where(function ($query) {
                    $query->where('status', 1)
                        ->orWhere('status', 2);
                });
            }
        } else {
            $query->where(function ($query) {
                $query->where('status', 1)
                    ->orWhere('status', 2);
            });
        }
        if ($request->currency > 0) {
            $query->where('currency_id', $request->currency);
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

    public function edit(int $order_id)
    {
        $response['order'] = PosOrderFacade::get($order_id);
        return Inertia::render('Receipt/edit', $response);
    }

    public function bills(int $order_id)
    {
        return PosReceiptFacade::getBills($order_id);
    }

    public function creditPay(CreateBillRequest $request, int $order_id)
    {
        return PosReceiptFacade::creditPay($request->all(), $order_id);
    }

    public function updateCreditPay(UpdateBillRequest $request)
    {
        return PosReceiptFacade::updateCreditPay($request->all());
    }
}
