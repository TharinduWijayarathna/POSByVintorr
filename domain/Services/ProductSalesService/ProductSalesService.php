<?php

namespace domain\Services\ProductSalesService;

use App\Models\PosOrderItem;
use Illuminate\Support\Facades\DB;

/**
 * ProductSalesService
 * php version 8
 *
 * @category Service
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class ProductSalesService
{
    private $pos_order_item;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->pos_order_item = new PosOrderItem;
    }

    /**
     * getOrderItems
     *
     * @param  mixed  $pos_order_ids
     * @return void
     */
    public function getOrderItems($pos_order_ids)
    {
        return $this->pos_order_item
            ->whereIn('pos_order_items.order_id', $pos_order_ids)
            ->select(
                'pos_order_items.product_id',
                DB::raw('SUM(pos_order_items.quantity) as total_quantity'),
                DB::raw('SUM(pos_order_items.total) as total_amount')
            )
            ->groupBy('pos_order_items.product_id')
            ->orderByDesc('total_quantity')
            ->orderByDesc('total_amount');
    }
}
