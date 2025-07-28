<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReturnItems\AddItemsRequest;
use App\Http\Requests\ReturnItems\AddReturnRequest;
use App\Http\Resources\DataResource;
use App\Models\PosOrder;
use App\Models\User;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\PosOrderItemFacade\PosOrderItemFacade;
use domain\Facades\ReturnFacade\ReturnFacade;
use domain\Facades\UserFacade\UserFacade;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReturnsController extends ParentController
{
    public function view()
    {
        // $response = UserFacade::retrieveHost();
        return Inertia::render('Returns/view');
    }

    public function index()
    {
        $order = ReturnFacade::getOrCreate();

        return redirect()->route('cart.return.process', $order->id);
    }

    public function editPage(int $order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            $response['order_id'] = $order_id;

            return Inertia::render('Returns/edit', $response);

        } else {
            throw new Exception('Access denied', 1);
        }
    }

    public function all(Request $request)
    {
        $query = PosOrder::query()->where('status', 4)->orderBy('updated_at', 'desc');
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

    public function deletedAll(Request $request)
    {
        $query = PosOrder::query()->where('status', 4)->orderBy('updated_at', 'desc')->onlyTrashed();
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

    public function process($order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::get($order_id);
            if ($order->status == 1 || $order->created_by != Auth::id()) {
                $response['alert-danger'] = 'Order Can\'t be processed.';

                return redirect()->route('cart')->with($response);
            } else {
                $user_id = Auth::id();
                ReturnFacade::removePosOrderItems($order_id);
                ReturnFacade::resetReturnDraft($order_id, $user_id);

                // $response = UserFacade::retrieveHost();
                return Inertia::render('Returns/index');
            }

        } else {
            throw new Exception('Access denied', 1);
        }
    }

    public function get(string $order_code)
    {
        return ReturnFacade::get($order_code);
    }

    public function getOrder(string $order_code)
    {
        return ReturnFacade::getOrder($order_code);
    }

    public function returnBill(Request $return_details)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $data['order_id'] = $return_details->order_id[0];
            $data['return_value'] = $return_details->return_value;
            $data['remark'] = $return_details->remark;
            PosOrderItemFacade::returnUpdate($return_details->orderItems);
            PosOrderFacade::returnUpdate($return_details->order_id[0]);
        }
    }

    public function addItems(AddItemsRequest $request, $order_id = null)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            if ($order_id == null) {
                $order = ReturnFacade::getOrCreate();

                return ReturnFacade::addItems($request->all(), $order->id);
            } else {
                return ReturnFacade::addItems($request->all(), $order_id);
            }
        }
    }

    public function getReturnProduct($order_id = null)
    {
        if ($order_id == null) {
            $order = ReturnFacade::getOrCreate();

            return ReturnFacade::getReturnProduct($order);
        } else {
            $order = PosOrder::withTrashed()->find($order_id);

            return ReturnFacade::getReturnProduct($order);
        }
    }

    public function deleteItem($id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ReturnFacade::deleteItem($id);
        } else {
            throw new Exception('Access denied', 1);
        }
    }

    public function getTotals($order_id = null)
    {
        if ($order_id == null) {
            $order = ReturnFacade::getOrCreate();

            return ReturnFacade::getTotals($order->id);
        } else {
            return ReturnFacade::getTotals($order_id);
        }
    }

    public function setCustomer(Request $request, $order_id = null)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            if ($order_id == null) {
                $order = ReturnFacade::getOrCreate();

                return ReturnFacade::customerUpdate($request->id, $order->id);
            } else {
                return ReturnFacade::customerUpdate($request->id, $order_id);
            }

        } else {
            throw new Exception('Access denied', 1);
        }
    }

    public function returnOrder()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ReturnFacade::getOrCreate();
        }
    }

    public function returnEditOrder($order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ReturnFacade::returnEditOrder($order_id);
        }
    }

    public function removeCustomerId($order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ReturnFacade::removeCustomerId($order_id);
        }
    }

    public function returnDone(AddReturnRequest $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ReturnFacade::returnDone($request->all());
        }

    }

    public function returnUpdate(AddReturnRequest $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ReturnFacade::returnUpdate($request->all());
        }

    }

    public function delete($order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ReturnFacade::delete($order_id);
        }
    }

    public function deletedList()
    {
        // $response = UserFacade::retrieveHost();
        return Inertia::render('Returns/deleted');
    }

    public function restoreReturn($order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ReturnFacade::restoreReturn($order_id);
        }
    }
}
