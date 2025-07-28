<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('send-monthly-business-summary-command')->dailyAt('08.00');
        $schedule->command('send-monthly-customer-outstanding-report')->dailyAt('08.10');
        $schedule->command('reports:delete-old')->daily();
        $schedule->command('backup-export-command')->daily();
        $schedule->command('telescope:prune --hours=48')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
