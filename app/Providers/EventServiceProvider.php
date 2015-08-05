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
			'App\Listeners\UserUpdated\IdentityReverification',
		],
		'App\Events\UserCreated' => [
			'App\Listeners\UserCreated\SendWelcomeEmail'
		],
		'App\Events\UserLoginFailed' => [
			'App\Listeners\UserLoginFailed\LogAttempt',
			'App\Listeners\UserLoginFailed\SendResetEmail'
		],
		'App\Events\MotionUpdated' => [
			'App\Listeners\MotionUpdated\SendNotificationEmail',
			'App\Listeners\MotionUpdated\RemoveVotes'
		],
		'App\Events\VoteUpdated' => [
			'App\Listeners\VoteUpdated\CheckCommentVotes'
		]
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
