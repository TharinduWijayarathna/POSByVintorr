<?php

namespace Database\Seeders;

use App\Models\PosOrder;
use Illuminate\Database\Seeder;

class PosOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PosOrder::factory(500)->create();
    }
}
