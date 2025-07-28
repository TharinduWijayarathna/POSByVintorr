<?php

namespace domain\Services\OutstandingReportService;

use Carbon\Carbon;

/**
 * OutstandingReportService
 * php version 8
 *
 * @category Service
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class OutstandingReportService
{
    /**
     * calculateAgesAndTotals
     *
     * @param  mixed  $orders
     * @return void
     */
    public function calculateAgesAndTotals($orders)
    {
        // Calculate age and totals for each order
        foreach ($orders as $order) {
            if (! $order->date) {
                // If date is not set, use created_at
                $order->date = Carbon::parse($order->created_at)->format('Y-m-d');
            }
            if (! $order->due_date) {
                // If due date is not set, use date
                $order->due_date = $order->date;
            }

            // Calculate age
            $order = $this->calculateAge($order);
        }

        return $orders;
    }

    /**
     * calculateAge
     *
     * @param  mixed  $order
     * @return void
     */
    private function calculateAge($order)
    {
        // Calculate difference between due date and current date
        $current_date = Carbon::now();
        $due_date = Carbon::parse($order->due_date);
        $days = $current_date->diffInDays($due_date);

        // Set age
        $order->age = $days;

        // Initialize age groups
        $order->age_column = null;

        // Determine the age range column
        if ($days <= 30) {
            $order->age_column = 'age_0_30';
        } elseif ($days <= 60) {
            $order->age_column = 'age_31_60';
        } elseif ($days <= 90) {
            $order->age_column = 'age_61_90';
        } else {
            $order->age_column = 'over_90';
        }
    }

    /**
     * calculateTotals
     *
     * @param  mixed  $orders
     * @return void
     */
    public function calculateTotals($orders)
    {
        // Initialize totals array
        $totals = [
            'paid_total' => 0,
            'age_0_30_total' => 0,
            'age_31_60_total' => 0,
            'age_61_90_total' => 0,
            'over_90_total' => 0,
            'total' => 0,
            'outstanding_total' => 0,
        ];

        // Calculate totals
        foreach ($orders as $order) {
            // Calculate paid total
            $totals['paid_total'] += $order['customer_paid'];
            // Calculate total
            $totals['total'] += $order['total'];

            // Calculate unpaid amount
            $unpaid_amount = $order['total'] - $order['customer_paid'];

            // Determine the age range column and add to the total
            switch ($order['age_column']) {
                case 'age_0_30':
                    $totals['age_0_30_total'] += $unpaid_amount;
                    break;
                case 'age_31_60':
                    $totals['age_31_60_total'] += $unpaid_amount;
                    break;
                case 'age_61_90':
                    $totals['age_61_90_total'] += $unpaid_amount;
                    break;
                case 'over_90':
                    $totals['over_90_total'] += $unpaid_amount;
                    break;
            }

            // Calculate outstanding total
            $totals['outstanding_total'] += $order['total'] - $order['customer_paid'];
        }

        return $totals;
    }
}
