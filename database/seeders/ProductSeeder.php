<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Assuming you want to create 50 sample products
        Product::factory(100)->create();
    }
}
