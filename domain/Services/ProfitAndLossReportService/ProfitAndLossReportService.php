<?php

namespace domain\Services\ProfitAndLossReportService;

use App\Models\Expense;
use App\Models\PosOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;

/**
 * ProfitAndLossReportService
 * php version 8
 *
 * @category Service
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class ProfitAndLossReportService
{
    private $posOrderQuery;
    private $transactionQuery;
    private $returnsQuery;
    private $payrollQuery;
    private $otherExpensesQuery;

    // Constructor to initialize the queries
    public function __construct()
    {
        $this->posOrderQuery = PosOrder::query();
        $this->transactionQuery = Transaction::query()->where('type', 5);
        $this->returnsQuery = PosOrder::query()->where('is_return', 1);
        $this->payrollQuery = Expense::query()->where('type', 1);
        $this->otherExpensesQuery = Expense::query()->where('type', 0);
    }

    /**
     * fetchData
     *
     * @param  mixed $request
     * @return array
     */
    public function fetchData(Request $request)
    {
        // Apply filters to each query
        $this->applyFilters($this->posOrderQuery, $request);
        $this->applyFilters($this->transactionQuery, $request);
        $this->applyFilters($this->returnsQuery, $request);
        $this->applyFilters($this->otherExpensesQuery, $request);
        $this->applyFilters($this->payrollQuery, $request);
        // Get the sum of sales on cash
        $sales_on_cash = $this->posOrderQuery->clone()->where('is_return', 0)->sum('customer_paid');

        // Get the total sales and the total balance
        $total_sales = $this->posOrderQuery->clone()->where('is_return', 0)->sum('total');

        // Calculate the sum of sales on credit
        $sales_on_credit = $total_sales - $sales_on_cash;

        // Calculate the positive manual transactions
        $positive_manual_transactions = $this->transactionQuery->clone()->where('sign', 1)->sum('amount');
        $negative_manual_transactions = $this->transactionQuery->clone()->where('sign', 0)->sum('amount');

        // Get the sum of returns
        $returns = $this->returnsQuery->sum('total');

        // Get the sum of payroll
        $payroll = $this->payrollQuery->sum('amount');

        // Get the sum of otherExpenses
        $otherExpenses = $this->otherExpensesQuery->sum('amount');

        // Calculate the profit
        $profit =  ($sales_on_cash + $sales_on_credit + $positive_manual_transactions) - (abs($returns) + abs($otherExpenses) + abs($payroll) + abs($negative_manual_transactions));

        // Calculate the profit percentage
        $profit_percentage = ($profit / ($sales_on_cash + $sales_on_credit + $positive_manual_transactions)) * 100;

        return [
            'sales_on_cash' => $sales_on_cash,
            'sales_on_credit' => $sales_on_credit,
            'positive_manual_transactions' => $positive_manual_transactions,
            'negative_manual_transactions' => $negative_manual_transactions,
            'returns' => $returns,
            'payroll' => $payroll,
            'otherExpenses' => $otherExpenses,
            'profit' => $profit,
            'profit_percentage' => $profit_percentage
        ];
    }

    /**
     * applyFilters
     *
     * @param  mixed $query
     * @param  mixed $request
     * @return void
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->has('search_data_from_date')) {
            $query->whereDate('date', '>=', $request->search_data_from_date);
        }

        if ($request->has('search_data_to_date')) {
            $query->whereDate('date', '<=', $request->search_data_to_date);
        }

        if ($request->has('search_data_currency')) {
            $query->where('currency_id', $request->search_data_currency);
        }
    }
}
