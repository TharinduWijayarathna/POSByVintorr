<?php

namespace Database\Seeders;

use App\Models\PosCustomer;
use Illuminate\Database\Seeder;

class PosCustomerSeeder extends Seeder
{
    public function run()
    {
        PosCustomer::factory()->count(150)->create();
    }
}
