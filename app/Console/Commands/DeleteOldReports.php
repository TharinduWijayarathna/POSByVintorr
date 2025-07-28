<?php

namespace App\Console\Commands;

use domain\Facades\ReportFacade\ReportFacade;
use Illuminate\Console\Command;

class DeleteOldReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old reports from the storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ReportFacade::deleteOldReports();
    }
}
