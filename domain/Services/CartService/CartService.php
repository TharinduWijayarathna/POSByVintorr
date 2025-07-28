<?php

namespace domain\Services\CartService;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CartService
{
    protected $cart;

    protected $cart_items;

    protected $pos_order;

    protected $pos_order_items;

    protected $customers;

    protected $products;

    public function __construct()
    {
        $this->cart = new Cart;
        $this->cart_items = new CartItem;
        $this->pos_order = new PosOrder;
        $this->pos_order_items = new PosOrderItem;
        $this->customers = new Customer;
        $this->products = new Product;
    }

    public function getOrCreateCart()
    {
        if (Cookie::get('active_cart')) {
            $key = Cookie::get('active_cart');

            $check = $this->cart->checkKey($key);

            if ($check == 1) {
                $cart = $this->cart->getByKey($key);
                if ($cart->status == 0) {
                    return $cart;
                } else {
                    $newKey = str::random(100);
                    Cookie::queue('active_cart', $newKey, 9999999);

                    $data['key'] = $newKey;
                    $cart = $this->cart->create($data);

                    return $cart;
                }
            } else {
                $newKey = str::random(100);
                Cookie::queue('active_cart', $newKey, 9999999);

                $data['key'] = $newKey;
                $cart = $this->cart->create($data);

                return $cart;
            }
        } else {
            $newKey = Str::random(100);

            Cookie::queue('active_cart', $newKey, 9999999);

            $data['key'] = $newKey;
            $cart = $this->cart->create($data);

            return $cart;
        }
    }

    public function addToCart($product_data, $cart_id)
    {
        $cart_item = $this->cart_items->where('product_id', $product_data['id'])->where('cart_id', $cart_id)->first();
        if (! empty($cart_item)) {
            if ($cart_item->quantity < 10000) {
                $cart_item->quantity += 1;
                $cart_item->total = $cart_item->quantity * $cart_item['unit_price'];
                $cart_item->save();
            } else {
                $errorMessage = 'Quantity cannot exceed 10,000';

                return response()->json([
                    'errors' => [
                        'quantity' => [$errorMessage],
                    ],
                    'quantity' => [$errorMessage],
                    0 => $errorMessage,
                    'message' => $errorMessage,
                ], 400);
            }
        } else {
            $data = [
                'cart_id' => $cart_id,
                'product_id' => $product_data['id'],
                'quantity' => 1,
                'unit_price' => $product_data['selling'],
                'total' => 1 * $product_data['selling'],
            ];
            $this->cart_items->create($data);
        }
    }

    public function getCartProduct($cart_id)
    {
        return $this->cart_items->getAll($cart_id);
    }

    public function decreaseQty($product_id, int $cart_id)
    {
        $cart = $this->cart_items->where('product_id', $product_id)->where('cart_id', $cart_id)->first();
        if ($cart) {
            if ($cart->quantity > 1) {
                $cart->quantity -= 1;
                $cart->total = $cart->quantity * $cart['unit_price'];
                $cart->save();
            } else {
                $this->cart_items->where('product_id', $product_id)->where('cart_id', $cart_id)->delete();
            }
        }
    }

    public function increaseQty($product_id, int $cart_id)
    {
        $cart = $this->cart_items->where('product_id', $product_id)->where('cart_id', $cart_id)->first();
        if ($cart) {
            if ($cart->quantity < 10000) {
                $cart->quantity += 1;
                $cart->total = $cart->quantity * $cart['unit_price'];
                $cart->save();
            } else {
                $errorMessage = 'Quantity cannot exceed 10,000';

                return response()->json([
                    'errors' => [
                        'quantity' => [$errorMessage],
                    ],
                    'quantity' => [$errorMessage],
                    0 => $errorMessage,
                    'message' => $errorMessage,
                ], 400);
            }
        }
    }

    public function changeQty($product_id, int $cart_id, int $quantity)
    {
        $cart = $this->cart_items->where('product_id', $product_id)->where('cart_id', $cart_id)->first();
        if ($cart) {
            if ($quantity <= 10000) {
                $cart->quantity = $quantity;
                $cart->total = $cart->quantity * $cart['unit_price'];
                $cart->save();
            } else {
                $cart->quantity = 10000;
                $cart->total = $cart->quantity * $cart['unit_price'];
                $cart->save();
                $errorMessage = 'Quantity cannot exceed 10,000';

                return response()->json([
                    'errors' => [
                        'quantity' => [$errorMessage],
                    ],
                    'quantity' => [$errorMessage],
                    0 => $errorMessage,
                    'message' => $errorMessage,
                ], 400);
            }
        }
    }

    public function deleteCartProduct(int $product_id, int $cart_id)
    {
        return $this->cart_items->where('product_id', $product_id)->where('cart_id', $cart_id)->delete();
    }

    public function getCount(int $cart_id)
    {
        $cartProductCount = $this->cart_items->where('cart_id', $cart_id)->sum('quantity');

        return $cartProductCount;
    }

    public function confirmOrder(int $customer_id, int $cart_id)
    {
        $customer = $this->customers->find($customer_id);

        $count = $this->pos_order->where('code', 'like', 'P%')->count();

        $code = 'P'.sprintf('%05d', $count + 1);
        $check = $this->pos_order->getCode($code);

        while ($check) {
            $count++;
            $code = 'P'.sprintf('%05d', $count);
            $check = $this->pos_order->getCode($code);
        }

        $data['code'] = $code;
        $data['status'] = 5;
        $data['customer_id'] = $customer_id;
        if (isset($customer->name)) {
            $data['customer_name'] = $customer->name;
        }
        if (isset($customer->address)) {
            $data['customer_address'] = $customer->address;
        }
        if (isset($customer->email)) {
            $data['customer_email'] = $customer->email;
        }
        if (isset($customer->contact)) {
            $data['customer_mobile'] = $customer->contact;
        }

        $orderProducts = $this->cart_items->where('cart_id', $cart_id)->get();
        foreach ($orderProducts as $orderProduct) {
            $product = $this->products->find($orderProduct->product_id);
            if (! $product) {
                $deleted_product = $this->products->withTrashed()->find($orderProduct->product_id);

                return response()->json([
                    'status' => 'error',
                    'message' => $deleted_product->name.' is not available anymore. Please remove that item before checking out.',
                ], 400);
            } elseif ($product->product_type == $this->products::PRODUCT_TYPE['stockable'] && $product->stock_quantity <= 0 && $orderProduct->quantity > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => $product->name.' is out of stock. Please remove that item before checking out.',
                ], 400);
            } elseif ($product->product_type == $this->products::PRODUCT_TYPE['stockable'] && $product->stock_quantity > 0 && $orderProduct->quantity > $product->stock_quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'We only have '.$product->stock_quantity.' of '.$product->name.' in our stock. Please adjust the quantity before checking out.',
                ], 400);
            }
        }

        $posOrder = $this->pos_order->create($data);
        $posOrderId = $posOrder->id;

        $orderTotal = 0;
        foreach ($orderProducts as $orderProduct) {
            $product = $this->products->find($orderProduct->product_id);

            $posOrderItemData = [
                'order_id' => $posOrderId,
                'product_id' => $orderProduct->product_id,
                'product_name' => $product->name,
                'quantity' => $orderProduct->quantity,
                'unit_price' => $orderProduct->unit_price,
                'sub_total' => $orderProduct->total,
                'total' => $orderProduct->total,
            ];

            $orderTotal = $orderTotal + $orderProduct->total;

            $this->pos_order_items->create($posOrderItemData);
        }

        $posOrder->sub_total = $orderTotal;
        $posOrder->total = $orderTotal;
        $posOrder->save();

        $cart = $this->cart->where('id', $cart_id)->first();
        $cart->status = 1;

        return $cart->save();
    }
}
