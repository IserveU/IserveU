<?php

namespace App\Console;

use App\Jobs\Emails\PrepareAdminSummary;
use App\Jobs\Emails\PrepareMotionSummary;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Setting;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        '\App\Console\Commands\Setup\InitializeApp',
        '\App\Console\Commands\Setup\Defaults',
        '\App\Console\Commands\ProcessCSV',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            dispatch(new PrepareAdminSummary());
        })->daily();

        $schedule->call(function () {
            dispatch(new PrepareMotionSummary());
        })->hourly();

        // Defaults to twice a day
        if (Setting::get('site.backup')) {
            $schedule->call(function () {
                \Artisan::call('backup:run');
            })->cron(Setting::get('site.backup'));
        }
    }
}
