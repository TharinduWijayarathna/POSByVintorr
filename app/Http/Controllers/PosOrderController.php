<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosOrderItem\UpdateOrderProductRequest;
use App\Http\Requests\PosOrderItem\UpdateQtyRequest;
use App\Http\Requests\PosOrderItem\UpdateUnitPriceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\ProductFacade\ProductFacade;
use domain\Facades\SalesPersonManagementFacade\SalesPersonManagementFacade;
use domain\Facades\TaxFacade\TaxFacade;
use Illuminate\Support\Facades\Auth;

/**
 * PosOrder Controller
 * php version 8
 *
 * @category Controller
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class PosOrderController extends ParentController
{
    /**
     * index
     * Load Cart index with new Order details
     *
     * @return void
     */
    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            return redirect()->route('cart.process', $order->id);
        }
    }

    /**
     * process
     *
     * @param  mixed $order_id
     * @return void
     */
    public function process($order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::get($order_id);
            if ($order->status == 1 || $order->created_by != Auth::id()) {
                $response['alert-danger'] = 'Order Can\'t be processed.';
                return redirect()->route('cart')->with($response);
            } else {
                $response['order'] = $order;
                return Inertia::render('Cart/index', $response);
            }
        }
    }

    /**
     * edit
     * Get the details of created order for update data
     *
     * @param  mixed $order_id
     * @return void
     */
    public function edit(int $order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::get($order_id);
            return redirect()->route('cart.process', $order->id);
        }
    }

    /**
     * cancel
     * To cancel created order
     *
     * @param  mixed $order_id
     * @return void
     */
    public function cancel(int $order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            PosOrderFacade::cancel($order_id);
        }
    }

    /**
     * hold
     * To hold created order
     *
     * @param  mixed $order_id
     * @return void
     */
    public function hold(int $order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            PosOrderFacade::hold($order_id);
        }
    }

    /**
     * update
     * To update created order
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            return PosOrderFacade::customerUpdate($request->id, $order->id);
        }
    }

    /**
     * finishGood
     * get all finish goods from material (for scan barcode )
     *
     * @param  string $barcode
     * @return void
     */
    public function finishGood(string $barcode)
    {
    }

    public function getFinishGood(int $product_id)
    {
    }

    /**
     * finishGoodAll
     * get all finish goods from material
     *
     *
     * @return void
     */
    public function finishGoodAll(Request $request)
    {
    }

    public function finishGoodByName(string $name)
    {
    }

    /**
     * discount
     * add discount to order
     *
     * @param  Request $request
     * @return void
     */
    public function discount(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosOrderFacade::discount($request->all());
        }
    }

    public function removeDiscount(int $order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            PosOrderFacade::removeDiscount($order_id);
        }
    }

    /**
     * get
     * get single POS order data using id
     *
     * @param  int $id
     * @return void
     */
    public function get(int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosOrderFacade::get($id);
        }
    }

    /**
     * salesPerson
     * det all sales people
     *
     * @return void
     */
    public function salesPerson()
    {
        // return SalesPersonManagementFacade::all();
    }

    public function getSalesPerson(int $id)
    {
        // return SalesPersonManagementFacade::get($id);
    }

    public function getByCode(string $bcode)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosOrderFacade::getByCode($bcode);
        }
    }

    public function delete(int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosOrderFacade::delete($id);
        }
    }

    /**
     * Add product to POS cart
     * @param mixed $product_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function selectProduct($product_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            $product = ProductFacade::getById($product_id);
            return PosOrderFacade::selectProduct($product, $order);
        }
    }

    /**
     * Get selected cart products
     * @return void
     */
    public function getOrderProduct()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            return PosOrderFacade::getOrderProduct($order);
        }
    }

    /**
     * Clear cart order
     * @return void
     */
    public function clearOrder()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            return PosOrderFacade::clearOrder($order->id);
        }
    }

    /**
     * Get cart total
     * @return void
     */
    public function getTotals()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            return PosOrderFacade::getTotals($order->id);
        }
    }

    /**
     * Decrease cart product qty
     * @param mixed $product_id
     * @return void
     */
    public function decreaseQty($product_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            $product = ProductFacade::getById($product_id);

            if ($product == null) {
                $product["id"] = $product_id;
            }

            return PosOrderFacade::decreaseQty($product, $order->id);
        }
    }

    /**
     * Increase cart product qty
     * @param mixed $product_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function increaseQty($product_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            $product = ProductFacade::getById($product_id);

            if ($product == null) {
                return response()->json(['error' => 'Product not available at the moment'], 404);
            }

            return PosOrderFacade::increaseQty($product, $order->id);
        }
    }

    /**
     * Update cart product qty & price
     * @param \App\Http\Requests\PosOrderItem\UpdateOrderProductRequest $request
     * @param int $pos_order_item_id
     * @return void
     */
    public function updateQty(UpdateOrderProductRequest $request, int $pos_order_item_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();

            return PosOrderFacade::updateQty($request, $pos_order_item_id, $order->id);
        }
    }

    public function changeUnitPrice(UpdateUnitPriceRequest $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            $product = ProductFacade::getById($request->product_id);
            return PosOrderFacade::changeUnitPrice($request->unit_price, $product, $order->id);
        }
    }

    /**
     * Remove selected item of cart
     * @param int $pos_order_item_id
     * @return void
     */
    public function removeItem(int $pos_order_item_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            return PosOrderFacade::removeItem($pos_order_item_id, $order->id);
        }
    }

    /**
     * Hold the cart
     * @return bool
     */
    public function holdCart()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            return PosOrderFacade::holdCart($order->id);
        }
    }

    /**
     * Remove cart customer
     * @param mixed $order_id
     * @return object|\App\Models\PosOrder|\Illuminate\Database\Eloquent\Model|null
     */
    public function removeCustomerId($order_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosOrderFacade::removeCustomerId($order_id);
        }
    }

    /**
     * Payment for the order
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function paymentDone(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosOrderFacade::paymentDone($request->all());
        }
    }
}
