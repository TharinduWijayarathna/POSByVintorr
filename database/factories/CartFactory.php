<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    public function definition()
    {
        return [
            'key' => Str::uuid(), // Generate unique key
            'status' => $this->faker->randomElement([0, 1]), // 'PENDING' or 'CONFIRMED'
        ];
    }
}
