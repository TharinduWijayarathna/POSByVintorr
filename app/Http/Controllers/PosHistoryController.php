<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Models\PosOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * PosHistory Controller
 * php version 8
 *
 * @category Controller
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class PosHistoryController extends ParentController
{
    /**
     * index
     * Load History index page (Orders history)
     *
     * @return void
     */
    public function index()
    {
        if (Auth::user()->can('access_invoice')) {
            return Inertia::render('History/index');
        } else {
            $response['alert-danger'] = 'You do not have permission to invoice.';

            return Inertia::render('History/index');
        }
    }

    /**
     * all
     * Get all orders for the history page. If there is a request, filter will bw worked.
     *
     * @param  mixed  $request
     * @return void
     */
    public function all(Request $request)
    {
        $query = PosOrder::query()->where('created_by', Auth::id())->orderBy('updated_at', 'desc');
        if (isset($request->search_order_customer)) {
            $query->where('customer_id', $request->search_order_customer);
        }
        if (isset($request->search_order_from_date) && isset($request->search_order_to_date)) {
            // dd($request->search_order_date);
            $query->whereBetween('created_at', [$request->search_order_from_date, $request->search_order_to_date]);
        } elseif (isset($request->search_order_from_date)) {
            // dd($request->search_order_date);
            $query->whereBetween('created_at', [$request->search_order_from_date, now()->format('Y-m-d')]);
        } elseif (isset($request->search_order_to_date)) {
            // dd($request->search_order_date);
            $query->whereDate('created_at', '<', $request->search_order_to_date);
        }
        $payload = QueryBuilder::for($query)
            ->allowedSorts(['id'])
            ->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->whereHas('customer', function (Builder $query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
                    $query->orWhereHas('customerTypeData', function (Builder $query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
                    $query->orWhere('created_by', 'like', "%{$value}%");
                    $query->orWhere('total', $value);
                    $query->orWhere('sub_total', $value);
                    $query->orWhere('discount', $value);
                })
            )
            ->paginate(request('per_page', config('basic.pagination_per_page')));

        return DataResource::collection($payload);
    }
}
