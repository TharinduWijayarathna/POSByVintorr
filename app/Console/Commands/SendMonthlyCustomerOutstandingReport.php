<?php

namespace App\Console\Commands;

use domain\Facades\MonthlyCustomerOutstandingFacade\MonthlyCustomerOutstandingFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMonthlyCustomerOutstandingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-monthly-customer-outstanding-report';

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
        Log::info('sendCustomerOutstandingMail inside handel');
        MonthlyCustomerOutstandingFacade::sendCustomerOutstandingMail();
    }
}
