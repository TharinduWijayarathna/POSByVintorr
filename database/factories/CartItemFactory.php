<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    public function definition()
    {
        $unitPrice = $this->faker->randomFloat(2, 10, 500); // Random unit price
        $quantity = $this->faker->numberBetween(1, 10);     // Random quantity
        $total = $unitPrice * $quantity;                   // Calculate total

        return [
            'cart_id' => Cart::factory(),                   // Link to a cart
            'product_id' => Product::factory(),             // Link to a product
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total' => $total,
        ];
    }
}
