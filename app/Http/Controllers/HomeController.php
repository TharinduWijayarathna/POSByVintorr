<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\CreateTargetsRequest;
use App\Models\BusinessDetail;
use App\Models\PosOrder;
use App\Models\User;
use Carbon\Carbon;
use domain\Facades\BillPaymentFacade\BillPaymentFacade;
use domain\Facades\ConfigurationFacade\ConfigurationFacade;
use domain\Facades\DashboardFacade\DashboardFacade;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends ParentController
{
    /**
     * The dashboard of the system
     *
     * @return \Inertia\Response
     */
    public function index()
    {

        $business_details = ConfigurationFacade::getDetails();

        if (! $business_details) {
            BusinessDetail::create([
                'currency_id' => 149,
            ]);
        }

        $full_date = Carbon::now();
        $date = $full_date->toDateString();
        $year = Carbon::now()->year;

        // Set the start and end dates
        $start_date = Carbon::createFromDate($year, 1, 1);
        $end_date = Carbon::createFromDate($year, 12, 31);

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $monthDates = [];

        while ($startDate->lte($endDate)) {
            $monthDates[] = $startDate->copy()->format('d');
            $startDate->addDay();
        }

        $response['month_dates'] = $monthDates;

        // Create an array to store the dates
        $dates = [];

        // Generate the dates and add them to the array
        while ($start_date < $end_date) {
            $dates[] = $start_date->format('Y-m');
            $start_date->addMonth();
        }

        $invoices = BillPaymentFacade::getInvoiceChartData();
        $sales = BillPaymentFacade::getSalesChartData();
        $today_invoice_count = BillPaymentFacade::getTodayInvoiceChartData();
        $today_sales_count = BillPaymentFacade::getTodaySalesChartData();

        $response['monthly_invoices'] = $invoices;
        $response['monthly_sales'] = $sales;
        $response['today_invoice'] = $today_invoice_count;
        $response['today_sales'] = $today_sales_count;
        $response['year_month'] = $dates;

        $hours = range(0, 24);

        $twelveMonths = DashboardFacade::getTwelveMonths();
        $getTwelveMonthsArray = DashboardFacade::getTwelveMonthsArray();
        $response['twelve_months'] = $getTwelveMonthsArray;

        $today_sales = PosOrder::whereDate('date', $date)->orderBy('total')->get();
        // assign prices ascending order to a array
        foreach ($today_sales as $key => $today_sale) {
            $today_sales_price[] = $today_sale->total;
        }

        // To get hours
        foreach ($hours as $key => $hour) {
            $startTime = str_pad($hour, 2, '0', STR_PAD_LEFT).':00';
            $datetime[] = $startTime;
        }

        $response['total_sales'] = $today_sales_price ?? 0;

        $response['total_system_sales'] = PosOrder::where('status', '>', 0)->sum('total');
        $response['total_system_invoices'] = PosOrder::where('status', '>', 0)->where('total', '>', 0)->count();

        // Today Sales
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $day = Carbon::now()->day;
        $response['today_sales_amount'] = PosOrder::where('status', 1)->whereDate('updated_at', $currentYear.'-'.$currentMonth.'-'.$day)->sum('total');
        // This Month Sales
        $response['month_sales_amount'] = PosOrder::where('status', 1)->whereYear('updated_at', $currentYear)->whereMonth('updated_at', $currentMonth)->sum('total');
        // Today Invoice Count
        $response['today_invoice_count'] = PosOrder::where('status', 1)->where('total', '>', 0)->whereDate('updated_at', $currentYear.'-'.$currentMonth.'-'.$day)->count();
        // This Month Invoice Count
        $response['month_invoice_count'] = PosOrder::where('status', 1)->where('total', '>', 0)->whereYear('updated_at', $currentYear)->whereMonth('updated_at', $currentMonth)->count();

        $response['base_currency_code'] = ConfigurationFacade::getBaseCurrency();
        $response['weekly_target'] = ConfigurationFacade::getWeeklyTarget();
        $response['monthly_target'] = ConfigurationFacade::getMonthlyTarget();
        $response['yearly_target'] = ConfigurationFacade::getYearlyTarget();
        $response['weekly_sales_amount'] = ConfigurationFacade::getTotalForThisWeek();
        $response['monthly_sales_amount'] = ConfigurationFacade::getTotalForThisMonth();
        $response['yearly_sales_amount'] = ConfigurationFacade::getTotalForThisYear();
        $response['weekly_target_percentage'] = ConfigurationFacade::getPercentageForThisWeek();
        $response['monthly_target_percentage'] = ConfigurationFacade::getPercentageForThisMonth();
        $response['yearly_target_percentage'] = ConfigurationFacade::getPercentageForThisYear();
        $response['total_outstanding'] = BillPaymentFacade::getTotalOutstanding();
        $response['total_bill_outstanding'] = BillPaymentFacade::getBillTotalOutstanding();
        $response['currency_wise_outstanding'] = BillPaymentFacade::getCurrencyWiseOutstanding();
        // $response['total_invoice_outstanding'] = BillPaymentFacade::getInvoiceTotalOutstanding();

        // Top Selling Products
        $thirtyDaysAgo = now()->subDays(30)->toDateString();
        $today = now()->toDateString();

        $query = PosOrder::query()
            ->whereBetween('date', [$thirtyDaysAgo, $today])
            ->orderBy('id', 'desc');

        $orderIds = $query->pluck('id');

        if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $queryItems = PosOrderFacade::getPopularProductsInspector($orderIds);
        } else {
            $queryItems = PosOrderFacade::getPopularProducts($orderIds);
        }

        $response['featured_products'] = $queryItems;
        // End Top Selling Products

        $response['monthly_expense_percentage'] = DashboardFacade::ExpensesPropsChartData();

        // $response['hours'] = $datetime;

        return Inertia::render('Dashboard/index', $response);
    }

    /**
     * Dashboard target amount save
     *
     * @return void
     */
    public function targetStore(CreateTargetsRequest $request)
    {
        if (isset($request['weekly_target'])) {
            ConfigurationFacade::saveWeeklyTarget($request['weekly_target']);
        }
        if (isset($request['monthly_target'])) {
            ConfigurationFacade::saveMonthlyTarget($request['monthly_target']);
        }
        if (isset($request['yearly_target'])) {
            ConfigurationFacade::saveYearlyTarget($request['yearly_target']);
        }
    }

    // public function getTwelveMonths($year)
    // {
    //     return DashboardFacade::getTwelveMonths($year);
    // }

    /**
     * Summary of Sales
     *
     * @return array
     */
    public function getSales(Request $request)
    {
        return DashboardFacade::getSales($request->all());
    }

    /**
     * Summary of Incomes
     *
     * @return array
     */
    public function getIncomes(Request $request)
    {
        return DashboardFacade::getIncomes($request->all());
    }

    /**
     * Summary of Expenses
     *
     * @return array
     */
    public function getExpenses(Request $request)
    {
        return DashboardFacade::getExpenses($request->all());
    }

    /**
     * Dashboard expenses chart
     *
     * @return array
     */
    public function ExpensesChartData(Request $request)
    {
        return DashboardFacade::ExpensesChartData($request->all());
    }

    /**
     * Summary of Transaction Balance
     *
     * @param  mixed  $currency_id
     * @return array
     */
    public function getTransactionBalance($currency_id)
    {
        return DashboardFacade::getTransactionBalance($currency_id);
    }

    /**
     * dashboard permissions
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDashboardController()
    {
        return DashboardFacade::getDashboardController();
    }

    /**
     * Update permissions
     *
     * @return void
     */
    public function updateDashboardController(Request $request)
    {
        return DashboardFacade::updateDashboardController($request->all());
    }

    /**
     * Summary of Total Tax Amount
     *
     * @param  mixed  $currency_id
     * @return float[]
     */
    public function getTotalTaxAmount($currency_id)
    {
        return DashboardFacade::getTotalTaxAmount($currency_id);
    }
}
