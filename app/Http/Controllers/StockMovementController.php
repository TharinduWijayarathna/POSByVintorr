<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Models\StockLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StockMovementController extends Controller
{
    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Reports/StockMovement/index');
    }

    public function all(Request $request)
    {

        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {

            $query = StockLog::query()->orderBy('date', 'desc');

            if (isset($request->search_product_id)) {
                $query->where('product_id', $request->search_product_id);
            }
            if (isset($request->search_product_from_date)) {
                $query->whereDate('date', '>=', $request->search_product_from_date);
            }
            if (isset($request->search_product_to_date)) {
                $query->whereDate('date', '<=', $request->search_product_to_date);
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
