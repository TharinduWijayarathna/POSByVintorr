<?php

namespace domain\Services\DashboardService;

use App\Models\BillPayment;
use App\Models\BusinessDetail;
use App\Models\DashboardItemController;
use App\Models\Expense;
use App\Models\PosOrder;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use Carbon\Carbon;
use domain\Facades\ExpenseFacade\ExpenseFacade;

class DashboardService
{
    protected $business_details;
    protected $pos_order;
    protected $expense;
    protected $bill_payment;
    protected $transaction;
    protected $dashboard_item_controller;

    public function __construct()
    {
        $this->business_details = new BusinessDetail();
        $this->pos_order = new PosOrder();
        $this->expense = new Expense();
        $this->bill_payment = new BillPayment();
        $this->transaction = new Transaction();
        $this->dashboard_item_controller = new DashboardItemController();
    }

    public function getTwelveMonths()
    {

        $monthsArray = [];

        $currentYear = Carbon::now()->format('Y');

        for ($i = 0; $i < 12; $i++) {
            $monthsArray[] = Carbon::create($currentYear, $i + 1, 1)->format('Y-m');
        }

        return $monthsArray;
    }

    function getTwelveMonthsArray()
    {
        $monthsArray = [];
        $currentYear = Carbon::now()->format('Y');

        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::createFromDate($currentYear, $i, 1)->format('M');
            $monthsArray[] = $month;
        }

        return $monthsArray;
    }

    public function getSales($data)
    {

        if ($data['currency_id'] == null) {
            $businessDetails = $this->business_details->first();
            $currency_id = $businessDetails->currency_id;
        } else {
            $currency_id = $data['currency_id'];
        }

        $salesData = [];
        $monthsArray = $this->getTwelveMonths();

        foreach ($monthsArray as $month) {
            // $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            // $monthEnd = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            $monthStart = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfMonth();
            $monthEnd = Carbon::createFromFormat('Y-m-d', $month . '-01')->endOfMonth();

            // Check if $year is provided and filter by that year if so
            if ($data['year']) {
                $monthStart->year($data['year']);
                $monthEnd->year($data['year']);
            }

            $sales = $this->pos_order->where('currency_id', $currency_id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->where('is_return', 0)
                ->sum('total');

            $salesData[] = $sales;
        }

        return $salesData;
    }

    public function getSalesForReport($data)
    {

        if ($data['currency_id'] == null) {
            $businessDetails = $this->business_details->first();
            $currency_id = $businessDetails->currency_id;
        } else {
            $currency_id = $data['currency_id'];
        }

        $salesData = [];
        $monthsArray = $this->getTwelveMonths();

        foreach ($monthsArray as $month) {
            $monthStart = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfMonth();
            $monthEnd = Carbon::createFromFormat('Y-m-d', $month . '-01')->endOfMonth();

            // Check if $year is provided and filter by that year if so
            if ($data['year']) {
                $monthStart->year($data['year']);
                $monthEnd->year($data['year']);
            }

            $sales = $this->pos_order->where('currency_id', $currency_id)
                ->whereBetween('date', [$data['start_date'], $data['end_date']])
                ->where('is_return', 0)
                ->sum('total');

            $salesData[] = $sales;
        }

        return $salesData;
    }

    public function getIncomes($data)
    {

        if ($data['currency_id'] == null) {
            $businessDetails = $this->business_details->first();
            $currency_id = $businessDetails->currency_id;
        } else {
            $currency_id = $data['currency_id'];
        }

        $incomesData = [];
        $monthsArray = $this->getTwelveMonths();

        foreach ($monthsArray as $month) {
            $monthStart = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfMonth();
            $monthEnd = Carbon::createFromFormat('Y-m-d', $month . '-01')->endOfMonth();

            // Check if $year is provided and filter by that year if so
            if ($data['year']) {
                $monthStart->year($data['year']);
                $monthEnd->year($data['year']);
            }

            $posOrders = $this->pos_order->where('currency_id', $currency_id)
                ->pluck('id');

            $incomes = $this->bill_payment->whereIn('order_id', $posOrders)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('accepted_amount');

            $transactions = $this->transaction
                ->where('currency_id', $currency_id)
                ->where('type', 5)
                ->where('sign', 1)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->get();

            $totalTransactionAmount = $transactions->sum('amount');

            $incomesData[] = $incomes + $totalTransactionAmount;
        }

        return $incomesData;
    }

    public function getIncomesForReport($data)
    {

        if ($data['currency_id'] == null) {
            $businessDetails = $this->business_details->first();
            $currency_id = $businessDetails->currency_id;
        } else {
            $currency_id = $data['currency_id'];
        }

        $incomesData = [];
        $monthsArray = $this->getTwelveMonths();

        foreach ($monthsArray as $month) {
            $monthStart = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfMonth();
            $monthEnd = Carbon::createFromFormat('Y-m-d', $month . '-01')->endOfMonth();

            // Check if $year is provided and filter by that year if so
            if ($data['year']) {
                $monthStart->year($data['year']);
                $monthEnd->year($data['year']);
            }

            $posOrders = $this->pos_order->where('currency_id', $currency_id)
                ->pluck('id');

            $incomes = $this->bill_payment->whereIn('order_id', $posOrders)
                ->whereBetween('date', [$data['start_date'], $data['end_date']])
                ->sum('accepted_amount');

            $transactions = $this->transaction
                ->where('currency_id', $currency_id)
                ->where('type', 5)
                ->where('sign', 1)
                ->whereBetween('date', [$data['start_date'], $data['end_date']])
                ->get();

            $totalTransactionAmount = $transactions->sum('amount');

            $incomesData[] = $incomes + $totalTransactionAmount;
        }

        return $incomesData;
    }

    public function getExpenses($data)
    {

        if ($data['currency_id'] == null) {
            $businessDetails = $this->business_details->first();
            $currency_id = $businessDetails->currency_id;
        } else {
            $currency_id = $data['currency_id'];
        }

        $expensesData = [];
        $monthsArray = $this->getTwelveMonths();

        foreach ($monthsArray as $month) {
            $monthStart = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfMonth();
            $monthEnd = Carbon::createFromFormat('Y-m-d', $month . '-01')->endOfMonth();

            // Check if $year is provided and filter by that year if so
            if ($data['year']) {
                $monthStart->year($data['year']);
                $monthEnd->year($data['year']);
            }

            $expenses = $this->expense->where(
                'currency_id',
                $currency_id
            )
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $transactions = $this->transaction
                ->where('currency_id', $currency_id)
                ->where('type', 5)
                ->where('sign', 0)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->get();

            $totalTransactionAmount = $transactions->sum('amount');

            $expensesData[] = $expenses + $totalTransactionAmount;
        }

        return $expensesData;
    }

    public function getExpensesForReport($data)
    {

        if ($data['currency_id'] == null) {
            $businessDetails = $this->business_details->first();
            $currency_id = $businessDetails->currency_id;
        } else {
            $currency_id = $data['currency_id'];
        }

        $expensesData = [];
        $monthsArray = $this->getTwelveMonths();

        foreach ($monthsArray as $month) {
            $monthStart = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfMonth();
            $monthEnd = Carbon::createFromFormat('Y-m-d', $month . '-01')->endOfMonth();

            // Check if $year is provided and filter by that year if so
            if ($data['year']) {
                $monthStart->year($data['year']);
                $monthEnd->year($data['year']);
            }

            $expenses = $this->expense->where(
                'currency_id',
                $currency_id
            )
                ->whereBetween('date', [$data['start_date'], $data['end_date']])
                ->sum('amount');

            $transactions = $this->transaction
                ->where('currency_id', $currency_id)
                ->where('type', 5)
                ->where('sign', 0)
                ->whereBetween('date', [$data['start_date'], $data['end_date']])
                ->get();

            $totalTransactionAmount = $transactions->sum('amount');

            $expensesData[] = $expenses + $totalTransactionAmount;
        }

        return $expensesData;
    }

    public function ExpensesPropsChartData()
    {
        $categories = ExpenseFacade::getExpenseCategories();

        $businessDetails = $this->business_details->first();
        $currency_id = $businessDetails->currency_id;

        $expense_category = [];
        $expenses = [];
        $expenses_amount = [];

        foreach ($categories as $category) {
            $amount = ExpenseFacade::getExpenseByPropsCategory($category->id, $currency_id);
            $totalExpenses = ExpenseFacade::getExpenseAmountByPropsCategory($category->id, $currency_id);
            if ($amount >= 0) {
                $expenses[] = floatval($amount);
                $expense_category[] = $category->name;
                $expenses_amount[] = floatval($totalExpenses);
            }
        }

        $payrolls = ExpenseFacade::getExpenseAmountByPropsCurrencyId($currency_id);

        $category_count = sizeof($categories);

        $expenses[$category_count] = floatval($payrolls);
        $expense_category[$category_count] = 'Salary';

        $response['categories'] = $expense_category;
        $response['expenses'] = $expenses;
        $response['total_amount'] = $expenses_amount;
        return $response;
    }
    public function ExpensesChartData($data)
    {
        $categories = ExpenseFacade::getExpenseCategories();
        if ($data['currency_id'] == null) {
            $businessDetails = $this->business_details->first();
            $currency_id = $businessDetails->currency_id;
        } else {
            $currency_id = $data['currency_id'];
        }

        $expense_category = [];
        $expenses = [];

        foreach ($categories as $category) {
            $amount = ExpenseFacade::getExpenseByCategory($category->id, $currency_id, $data['monthly_id']);

            if ($amount > 0) {  // Only include amounts greater than 0
                $expenses[] = floatval($amount);
                $expense_category[] = $category->name;
            }
        }

        $payrolls = ExpenseFacade::getPayrollByCurrency($currency_id, $data['monthly_id']);

        if ($payrolls > 0) {
            $expenses[] = floatval($payrolls);
            $expense_category[] = 'Salary';
        }

        $response['categories'] = $expense_category;
        $response['expenses'] = $expenses;
        return $response;
    }

    public function getTransactionBalance($currency_id)
    {
        if ($currency_id == 'null') {
            $businessDetails = $this->business_details->first();
            $currency_id = $businessDetails->currency_id;
        }

        $transactionBalance = TransactionBalance::where('currency_id', $currency_id)->first();
        $response['transactionBalance'] = $transactionBalance;
        return $response;
    }

    public function getDashboardController()
    {
        return $this->dashboard_item_controller->all();
    }

    public function updateDashboardController(array $data)
    {
        $dashboard_item = $this->dashboard_item_controller->get();

        foreach ($dashboard_item as $item) {

            $name = $item->name;

            if (array_key_exists($name, $data)) {
                $this->dashboard_item_controller->where('name', $name)->update(['status' => $data[$name]]);
            }
        }
    }

    public function getTotalTaxAmount($currency_id)
    {
        if ($currency_id == 'null') {
            $businessDetails = $this->business_details->first();
            $currency_id = $businessDetails->currency_id;
        }

        // first day of the month
        $firstDay = Carbon::now()->startOfMonth();
        // last day of the month
        $lastDay = Carbon::now()->endOfMonth();

        $totalTaxAmount = $this->pos_order->where('currency_id', $currency_id)->whereBetween('date', [$firstDay, $lastDay])->sum('total_tax');
        $response['totalTaxAmount'] = floatval($totalTaxAmount);

        return $response;
    }
}
