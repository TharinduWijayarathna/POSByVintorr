<?php

namespace App\Console\Commands;

use domain\Facades\DatabaseFacade\DatabaseFacade;
use Illuminate\Console\Command;

class BackupExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup-export-command';

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
        DatabaseFacade::backupExport();
    }
}
