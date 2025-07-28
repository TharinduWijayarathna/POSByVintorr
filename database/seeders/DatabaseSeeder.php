<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // CategorySeeder::class,
            UnitSeeder::class,
            CurrencySeeder::class,
            TimeZoneSeeder::class,
            DashboardItemControllerSeeder::class,
            DashboardItemSeeder::class,
            UserSeeder::class,
            // CustomerSeeder::class,
            PermissionSeeder::class,
            // BillPaymentSeeder::class,
            // CartSeeder::class,
            // CartItemSeeder::class,
            // ExpenseSeeder::class,
        ]);
    }
}
