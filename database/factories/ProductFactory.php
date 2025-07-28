<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'cost' => $this->faker->numberBetween(100, 500),
            'selling' => $this->faker->numberBetween(500, 1000),
            'stock_quantity' => $this->faker->numberBetween(10, 100),
            'product_category_id' => \App\Models\ProductCategory::factory(),
            'status' => $this->faker->boolean,
        ];
    }
}
