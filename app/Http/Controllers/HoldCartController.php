<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Models\User;
use App\Models\PosOrder;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\UserFacade\UserFacade;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class HoldCartController extends ParentController
{
    public function index()
    {
        // $response = UserFacade::retrieveHost();
        return Inertia::render('SavedCart/index');
    }

    public function all(Request $request)
    {
        $query = PosOrder::query()->where('type', 0)->whereIn('status', [2, 5])->orderBy('id', 'desc');
        if (isset($request->search_order_customer_id)) {
            if ($request->search_order_customer_id == 0) {
                $query->where('customer_id', null);
            } else {
                $query->where('customer_id', $request->search_order_customer_id);
            }
        }
        if (isset($request->search_order_status)) {
            if ($request->search_order_status > 0) {
                $query->where('status', $request->search_order_status);
            }
        }
        $payload = QueryBuilder::for($query)
            ->allowedSorts(['code'])
            ->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->Where('code', 'like', "%{$value}%");
                })
            )
            // ->allowedSorts(['code'])
            ->paginate(request('per_page', config('basic.pagination_per_page')));
        return DataResource::collection($payload);
    }

    public function edit()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return Inertia::render('SavedCart/edit');
        }
    }

    public function reActive(int $order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrder::where('id', $order_id)->first();
            if ($order->type == 0) {
                return PosOrderFacade::reActive($order_id);
            } else {
                return PosOrderFacade::reActiveInvoice($order_id);
            }
        } else {
            return response()->json(['message' => 'Access denied'], 400);
        }
    }

    public function deleteOrder(int $order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosOrderFacade::deleteOrder($order_id);
        } else {
            throw new Exception("Access denied", 1);
        }
    }
}
