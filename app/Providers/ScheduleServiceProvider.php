<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Schedule $schedule): void
    {
        $schedule->command('events:send-reminders')->dailyAt('09:00');
        $schedule->command('trips:end-expired')->dailyAt("00:00");
        $schedule->command('trip:send-report')->daily();


    }
}
