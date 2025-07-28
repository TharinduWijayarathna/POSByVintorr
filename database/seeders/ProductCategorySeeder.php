<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        ProductCategory::create(['name' => 'Electronics']);
        ProductCategory::create(['name' => 'Apparel']);
        ProductCategory::create(['name' => 'Home & Kitchen']);
    }
}
