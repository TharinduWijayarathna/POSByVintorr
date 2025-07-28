<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpenseCategory>
 */
class ExpenseCategoryFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name(), // Generate a unique category name
        ];
    }
}
