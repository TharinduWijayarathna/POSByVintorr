<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run()
    {
        Expense::factory()->count(150)->create(); // Create 100 Expense records
    }
}
