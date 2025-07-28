<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run()
    {
        ExpenseCategory::factory()->count(20)->create(); // Generate 20 ExpenseCategory records
    }
}
