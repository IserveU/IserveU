<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Setting;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
		'App\Console\Commands\EmailDailySummary',
		'App\Console\Commands\RankGeneration',
		'App\Console\Commands\ShuffleDefaultDelegations',
        '\App\Console\Commands\Setup\InitializeApp',
        '\App\Console\Commands\Setup\SetNewDefaults'
    ];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();

		$schedule->command('emails:daily')
				 ->daily();

		$schedule->command('motions:rankgeneration')
				 ->hourly();

		$schedule->command('settings:default')
				 ->hourly();
				 
		//            if(!$motion->lastestRank || $motion->lastestRank->created_at['carbon']->diffInMinutes($now) >= Setting::get('motion.minutes_between_rank_calculations',60)){

	}

}
