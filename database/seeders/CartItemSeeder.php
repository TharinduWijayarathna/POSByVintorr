<?php

namespace Database\Seeders;

use App\Models\CartItem;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    public function run()
    {
        CartItem::factory()->count(150)->create(); // Create 150 CartItem records
    }
}
