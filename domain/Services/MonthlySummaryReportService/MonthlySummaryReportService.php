<?php

namespace domain\Services\MonthlySummaryReportService;

use App\Jobs\SendMonthlyBusinessSummaryJob\SendMonthlyBusinessSummaryJob;
use App\Models\BusinessDetail;
use App\Models\Currency;
use App\Models\MonthlyBusinessSummaryEmail;
use App\Models\PosOrder;
use domain\Facades\DashboardFacade\DashboardFacade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use PDO;

/**
 * MonthlySummaryReportService
 * php version 8
 *
 * @category Service
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class MonthlySummaryReportService
{
    protected $business_detail;
    protected $pos_order;
    protected $pos_order_item;
    protected $dashboard_item;
    private $currency;
    private $business_summary_email;



    public function __construct()
    {
        $this->business_detail = new BusinessDetail();
        $this->pos_order = new PosOrder();
        $this->currency = new Currency();
        $this->business_summary_email = new MonthlyBusinessSummaryEmail();
    }

    public function sendMonthlySummaryReport()
    {

        try {
            Log::info('monthly summary report email start');
            $email_date = $this->business_summary_email->first();
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $previousMonth = Carbon::now()->subMonth()->format('F Y');
            $currencies = Currency::orderBy('code', 'asc')->get();
            $today_date = Carbon::now()->format('d');
            $custom_date = $email_date->value;


            if (isset($custom_date)) {
                if ($custom_date != 0 && $custom_date == $today_date) {

                    // define arrays
                    $actualMonthSails = [];
                    $actualMonthlyIncome = [];
                    $actualMonthlyExpense = [];

                    // if custom date is 31 calculation start from 1st of current month
                    if($custom_date == 31 ){
                        $custom_start_date  = 0;
                    }else{
                        $custom_start_date = $custom_date;
                    }

                    // set start and end dates
                    $start_date = Carbon::now()->subMonth()->format('Y-m-') . ($custom_start_date + 1);
                    $end_date = Carbon::now()->format('Y-m-') . $custom_date;

                    // Formatting dates
                    $from_date = Carbon::now()->subMonth()->day($custom_start_date + 1);
                    $to_date = Carbon::now()->day($custom_date);
                    $formatted_from_date = $from_date->format('jS F Y');
                    $formatted_to_date = $to_date->format('jS F Y');

                    Log::info('monthly summary report email body');
                    foreach ($currencies as $currency) {
                        $data['currency_id'] = $currency->id;
                        $data['year'] = $currentYear;
                        $data['start_date'] = $start_date;
                        $data['end_date'] = $end_date;

                        $monthlySails = DashboardFacade::getSalesForReport($data);
                        $monthlyIncomes = DashboardFacade::getIncomesForReport($data);
                        $monthlyExpenses = DashboardFacade::getExpensesForReport($data);

                        if ($monthlySails[$currentMonth - 2] != 0) {
                            $actualMonthSails[$currency->code] = $monthlySails[$currentMonth - 2];
                        }

                        if ($monthlyIncomes[$currentMonth - 2] != 0) {
                            $actualMonthlyIncome[$currency->code] = $monthlyIncomes[$currentMonth - 2];
                        }

                        if ($monthlyExpenses[$currentMonth - 2] != 0) {
                            $actualMonthlyExpense[$currency->code] = $monthlyExpenses[$currentMonth - 2];
                        }
                    }

                    $businessDetail = $this->business_detail->first();
                    $invoice = $this->pos_order->where('status', '>', 0)->where('status', '<', 4)->where('type', 1)->whereNotIn('status', [3])->whereBetween('date', [$start_date, $end_date])->count();
                    $posBills = $this->pos_order->where('status', '>', 0)->where('status', '<', 4)->where('type', 0)->whereNotIn('status', [3, 2])->whereBetween('date', [$start_date, $end_date])->count();

                    $image = $businessDetail->image_url;

                    if (empty($actualMonthlyExpense)) {
                        $actualMonthlyExpense[$businessDetail->currency_name] = 0;
                    }
                    if (empty($actualMonthSails)) {
                        $actualMonthSails[$businessDetail->currency_name] = 0;
                    }
                    if (empty($actualMonthlyIncome)) {
                        $actualMonthlyIncome[$businessDetail->currency_name] = 0;
                    }

                    $sendData['email'] = $businessDetail->email;
                    $sendData['subject'] = "Monthly business summary report of  $previousMonth";
                    $sendData['businessName'] = $businessDetail->name;
                    $sendData['monthlySail'] = $actualMonthSails;
                    $sendData['monthlyIncome'] = $actualMonthlyIncome;
                    $sendData['monthlyExpense'] = $actualMonthlyExpense;
                    $sendData['invoice'] = $invoice;
                    $sendData['posBills'] = $posBills;
                    $sendData['start_date'] = $formatted_from_date;
                    $sendData['end_date'] = $formatted_to_date;

                    SendMonthlyBusinessSummaryJob::dispatch($sendData, $sendData['email'], $image);
                    Log::info('monthly summary report email send successfully');
                }
            }

        } catch (\Throwable $th) {
            Log::info('email not send' . $th);
        }
    }
}
