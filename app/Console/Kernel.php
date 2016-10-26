<?php

namespace App\Console;

use App\Jobs\Emails\AdminSummary;
use App\Jobs\Emails\MotionSummary;
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
                dispatch(new AdminSummary());
            })->daily();
        }

        if (Setting::get('motion.email.user')) {
            $schedule->call(function () {
                dispatch(new MotionSummary());
            })->daily();
        }
    }
}
