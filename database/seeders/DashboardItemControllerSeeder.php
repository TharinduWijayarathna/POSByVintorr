<?php

namespace Database\Seeders;

use App\Models\DashboardItemController;
use Illuminate\Database\Seeder;

class DashboardItemControllerSeeder extends Seeder
{
    protected $dashboard_item_controller;

    public function __construct()
    {
        $this->dashboard_item_controller = new DashboardItemController;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dashboardItemControllers = [
            [
                'status' => '0',
                'dashboard_item_id' => 1,
                'name' => 'daily_total_sales_chart',
            ],
            [
                'status' => '0',
                'dashboard_item_id' => 2,
                'name' => 'daily_nob_chart',
            ],
            [
                'status' => '0',
                'dashboard_item_id' => 3,
                'name' => 'yearly_total_sales_chart',
            ],
            [
                'status' => '0',
                'dashboard_item_id' => 4,
                'name' => 'yearly_nob_chart',
            ],
            [
                'status' => '0',
                'dashboard_item_id' => 5,
                'name' => 'yearly_performance_chart',
            ],
            [
                'status' => '0',
                'dashboard_item_id' => 6,
                'name' => 'rol_reached_products',
            ],
            [
                'status' => '0',
                'dashboard_item_id' => 7,
                'name' => 'top_selling_products',
            ],
            [
                'status' => '0',
                'dashboard_item_id' => 8,
                'name' => 'expense_chart',
            ],

        ];

        foreach ($dashboardItemControllers as $dashboardItemController) {
            $this->dashboard_item_controller->create($dashboardItemController);
        }
    }
}
