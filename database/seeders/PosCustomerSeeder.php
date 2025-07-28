<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PosCustomer;

class PosCustomerSeeder extends Seeder
{
    public function run()
    {
        PosCustomer::factory()->count(150)->create();
    }
}
