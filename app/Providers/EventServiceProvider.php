<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'App\Events\UserUpdatedProfile'	=> [
			'App\Listeners\IdentityReverification',
		],
		'App\Events\UserRegistered' => [
			'App\Listeners\SendWelcomeEmail'
		],
		'App\Events\MotionUpdated' => [
			'App\Listeners\MotionUpdated\SendNotificationEmail',
			'App\Listeners\MotionUpdated\RemoveVotes'
		],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);
	}

}
