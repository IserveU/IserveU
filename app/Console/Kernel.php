<?php

namespace App\Console;

use App\Jobs\Emails\PrepareAdminSummary;
use App\Jobs\Emails\PrepareMotionSummary;
use App\Jobs\RunBackup;
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
        if (Setting::get('motion.email.admin')) {
            $schedule->call(function () {
                dispatch(new PrepareAdminSummary());
            })->daily();
        }

        if (Setting::get('motion.email.user')) {
            $schedule->call(function () {
                dispatch(new PrepareMotionSummary());
            })->weekly();
        }

        // Defaults to twice a day
        if (Setting::get('site.backup')) {
            $schedule->call(function () {
                dispatch(new RunBackup());
            })->cron(Setting::get('site.backup'));
        }
    }
}
