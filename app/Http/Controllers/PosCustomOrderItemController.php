<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosCustomOrderItem\CreatePosCustomOrderItemRequest;
use App\Http\Resources\DataResource;
use App\Models\PosCustomOrderItem;
use domain\Facades\PosCustomOrderItemFacade\PosCustomOrderItemFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * PosCustomOrderItemItem Controller
 * php version 8
 *
 * @category Controller
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class PosCustomOrderItemController extends ParentController
{
    /**
     * store
     * Store Customer Order details
     *
     * @return void
     */
    public function store(CreatePosCustomOrderItemRequest $req, int $order_id)
    {
        return PosCustomOrderItemFacade::store($req->all(), $order_id);
    }

    public function delete(int $item_id)
    {
        return PosCustomOrderItemFacade::delete($item_id);
    }

    /**
     * all
     * Get all Customer Order details. If there is request, filter will be worked
     *
     * @return void
     */
    public function all(int $order_id)
    {
        $query = PosCustomOrderItem::where('order_id', $order_id)->orderBy('id', 'desc');
        $payload = QueryBuilder::for($query)
            ->allowedSorts(['id'])
            ->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->whereHas('material', function (Builder $query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
                    $query->orWhere('description', 'like', "%{$value}%");
                    $query->orWhere('size', 'like', "%{$value}%");
                    $query->orWhere('quantity', 'like', "%{$value}%");
                    $query->orWhere('unit_price', 'like', "%{$value}%");
                    $query->orWhere('total', 'like', "%{$value}%");
                    $query->orWhere('remark', 'like', "%{$value}%");
                })
            )
            ->paginate(request('per_page', config('basic.pagination_per_page')));

        return DataResource::collection($payload);
    }

    public function edit(int $order_item_id)
    {
        $response['order'] = PosCustomOrderItemFacade::get($order_item_id);

        return Inertia::render('CustomOrder/edit', $response);
    }

    /**
     * get
     * Get single Customer Order details using Customer Order id
     *
     * @return void
     */
    public function get(int $customer_order_item_id)
    {
        return PosCustomOrderItemFacade::get($customer_order_item_id);
    }

    /**
     * update
     * Update Customer Order detail according to the request
     *
     * @return void
     */
    public function update(CreatePosCustomOrderItemRequest $request, int $customer_order_item_id)
    {
        return PosCustomOrderItemFacade::update($request->all(), $customer_order_item_id);
    }
}
