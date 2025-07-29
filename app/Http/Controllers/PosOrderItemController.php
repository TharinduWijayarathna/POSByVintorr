<?php

namespace App\Http\Controllers;

use domain\Facades\GiftVoucherFacade\GiftVoucherFacade;
use domain\Facades\MaterialFacade\MaterialFacade;
use domain\Facades\PosOrderItemFacade\PosOrderItemFacade;
use Illuminate\Http\Request;

/**
 * PosOrderItem Controller
 * php version 8
 *
 * @category Controller
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class PosOrderItemController extends ParentController
{
    /**
     * all
     * Get order items according to the order id
     *
     * @return void
     */
    public function all(int $order_id)
    {
        return PosOrderItemFacade::all($order_id);
    }

    /**
     * getMaterial
     * Get material details from "Material" table and store necessary data in Pos order item table
     *
     * @return void
     */
    public function getMaterial(string $barcode, Request $order)
    {
        $material = MaterialFacade::getByBarcode($barcode);
        if (! $material == null) {
            return PosOrderItemFacade::store($material, $order->all());
        }
    }

    /**
     * update
     * Update quantity and total price of the order item
     *
     * @return void
     */
    public function update(int $item_id, Request $cart)
    {
        PosOrderItemFacade::update($item_id, $cart->quantity);
    }

    /**
     * delete
     * Delete order item by order item id
     *
     * @return void
     */
    public function delete(int $item_id)
    {
        PosOrderItemFacade::delete($item_id);
    }

    /**
     * total
     * get all order item total sum
     *
     * @return void
     */
    public function total(int $order_id)
    {
        return PosOrderItemFacade::total($order_id);
    }

    public function discount(Request $request, int $order_id)
    {
        PosOrderItemFacade::discount($request->all(), $order_id);
    }

    public function voucher($code)
    {
        return GiftVoucherFacade::getVoucherByCode($code);
        // dd($data);
    }
}
