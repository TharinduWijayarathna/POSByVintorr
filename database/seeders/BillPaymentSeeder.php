<?php

namespace Database\Seeders;

use App\Models\BillPayment;
use Illuminate\Database\Seeder;

class BillPaymentSeeder extends Seeder
{
    public function run()
    {
        BillPayment::factory()->count(150)->create();
    }
}
