<?php

namespace App\Console\Commands;

use domain\Facades\MonthlySummaryReportFacade\MonthlySummaryReportFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMonthlyBusinessSummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-monthly-business-summary-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('MonthlySummaryReport inside handel');
        MonthlySummaryReportFacade::sendMonthlySummaryReport();
    }
}
