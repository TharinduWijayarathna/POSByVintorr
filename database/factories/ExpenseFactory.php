<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\ExpenseCategory;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    public function definition()
    {
        return [
            'code' => Str::uuid(), // Unique code
            'type' => $this->faker->randomElement([0, 1]), // 0 => 'Expense', 1 => 'Salary Payment'
            'expense_category_id' => ExpenseCategory::factory(), // Links to an ExpenseCategory
            'supplier_id' => Supplier::factory(),                 // Links to a Supplier
            'date' => $this->faker->date(),                       // Random date
            'description' => $this->faker->sentence(),            // Random description
            'remark' => $this->faker->text(100),                  // Random remark
            'currency_id' => 149,                 // Links to a Currency
            'amount' => $this->faker->randomFloat(2, 50, 5000),   // Random amount
        ];
    }
}
