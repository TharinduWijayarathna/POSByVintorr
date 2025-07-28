<?php

namespace Database\Seeders;

use App\Models\DashboardItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;

class DashboardItemSeeder extends Seeder
{
    protected $dashboard_item;

    public function __construct()
    {
        $this->dashboard_item = new DashboardItem();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dashboardItems = [
            [
                'name' => 'daily_total_sales_chart',
            ],
            [
                'name' => 'daily_nob_chart',
            ],
            [
                'name' => 'yearly_total_sales_chart',
            ],
            [
                'name' => 'yearly_nob_chart',
            ],
            [
                'name' => 'yearly_performance_chart',
            ],
            [
                'name' => 'rol_reached_products',
            ],
            [
                'name' => 'top_selling_products',
            ],
            [
                'name' => 'expense_chart',
            ],

        ];

        foreach ($dashboardItems as $dashboardItem) {
            $this->dashboard_item->create($dashboardItem);
        }
    }
}
