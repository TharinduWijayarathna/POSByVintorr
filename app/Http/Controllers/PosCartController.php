<?php

namespace App\Http\Controllers;

use App\Models\User;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\ProductFacade\ProductFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PosCartController extends ParentController
{
    /**
     * Create or get cart
     *
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function index(string $type = 'sidebar')
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();

            return redirect()->route('cart.process', ['order_id' => $order->id, 'type' => $type]);
        }
    }

    /**
     * get cart
     *
     * @param  mixed  $order_id
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function process($order_id, $type)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::get($order_id);

            if ($order->status == 1 || $order->created_by != Auth::id()) {
                $response['alert-danger'] = 'Order Can\'t be processed.';

                return redirect()->route('cart')->with($response);
            } else {
                if ($type == 'fullscreen') {
                    return Inertia::render('Cart/FullScreenPOS');
                }

                return Inertia::render('Cart/index');
            }
        }
    }

    public function finishGoodByNameBarcode(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
                return ProductFacade::finishGoodByNameBarcodeInspector($request->name, $request->product_category_id, $request->order_id);
            } else {
                return ProductFacade::finishGoodByNameBarcode($request->name, $request->product_category_id, $request->order_id);
            }
        }
    }
}
