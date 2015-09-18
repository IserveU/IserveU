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
		'App\Events\UserUpdated'	=> [
			'App\Listeners\User\AddUserModificationEntry',
			'App\Listeners\User\IdentityReverification',
		],
		'App\Events\UserCreated' => [
			'App\Listeners\User\AddUserModificationEntry',
			'App\Listeners\User\SetRememberToken',
			'App\Listeners\User\SendWelcomeEmail',
			'App\Listeners\User\CreateDefaultDelegations'
		],
		'App\Events\UserLoginFailed' => [
			'App\Listeners\User\LogAttempt',
			'App\Listeners\User\SendResetEmail'
		],
		'App\Events\UserDeleted' => [
			'App\Listeners\User\DeleteUser'
		],
		'App\Events\MotionUpdated' => [
			'App\Listeners\Motion\SendNotificationEmail',
			'App\Listeners\Motion\RemoveVotes'
		],
		'App\Events\VoteCreated' => [
			'App\Listeners\Vote\SetDeferedToVotes',
			'App\Listeners\Motion\BalanceDeferredVotes'
		],
		'App\Events\VoteUpdated' => [
			'App\Listeners\Vote\CheckCommentVotes',
			'App\Listeners\Vote\SetDeferedToVotes',
			'App\Listeners\Motion\BalanceDeferredVotes'
		],
		'App\Events\UserForgotPassword' => [
			'App\Listeners\User\SetRememberToken',
			'App\Listeners\User\SendResetEmail',
			'App\Listeners\User\SetRandomPassword'
		],
		'App\Events\MotionCreated' => [
			'App\Listeners\Motion\CreateDeferredVotes',
		],
		'App\Events\DepartmentCreated' => [
			'App\Listeners\Department\CreateDepartmentDelegations',
		],
		'App\Events\CommentDeleted' => [
			'App\Listeners\Comment\DeleteCommentVotes',
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
