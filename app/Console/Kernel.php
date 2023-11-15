<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

         $schedule->command('telescope:clear')->dailyAt("03:00");

         $schedule->command('onec:inventory')->dailyAt("21:00");
         $schedule->command('onec:order')->dailyAt("21:05");
         $schedule->command('onec:receipt')->dailyAt("21:10");
         $schedule->command('onec:refund')->dailyAt("21:15");
         $schedule->command('onec:reject')->dailyAt("21:20");
         $schedule->command('onec:moving')->dailyAt("21:25");
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
