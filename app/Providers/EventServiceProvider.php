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
		// 'App\Events\UserUpdating'	=> [
		// ],
		'App\Events\UserUpdated'	=> [
			'App\Listeners\User\AddUserModificationEntry',
			'App\Listeners\User\IdentityReverification',
			'App\Listeners\User\DeleteUnattachedFiles',
			'App\Listeners\User\CheckUserRoles',
		],
		'App\Events\UserCreated' => [
			'App\Listeners\User\SetRememberToken',
			'App\Listeners\User\SendWelcomeEmail',
		//	'App\Listeners\User\CreateDefaultDelegations'
		],
		'App\Events\UserLoginFailed' => [
			'App\Listeners\User\LogAttempt', // Also locks accounts
			'App\Listeners\User\SetRememberToken', //Doesn't reset password
			'App\Listeners\User\SendAccountLockEmail',
		],
		'App\Events\UserLoginSucceeded' => [
			'App\Listeners\User\ClearLockFields',
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
			//'App\Listeners\Motion\BalanceDeferredVotes' //Not needed with one councilor, not a big issue immediately
		],
		'App\Events\VoteUpdated' => [
			'App\Listeners\Vote\CheckCommentVotes',
			'App\Listeners\Vote\SetDeferedToVotes',
			//'App\Listeners\Motion\BalanceDeferredVotes',  //Not needed with one councilor, not a big issue immediately
			'App\Listeners\Comment\ClearMotionCommentCache',
		],
		'App\Events\MotionCreated' => [
			'App\Listeners\Motion\CreateDeferredVotes',
		],
		'App\Events\DepartmentCreated' => [
			'App\Listeners\Department\CreateDepartmentDelegations',
		],
		'App\Events\CommentDeleted' => [
			'App\Listeners\Comment\DeleteCommentVotes',
			'App\Listeners\Comment\ClearMotionCommentCache',
		],
		'App\Events\CommentCreated' => [
			'App\Listeners\Comment\ClearMotionCommentCache',
		],
		'App\Events\CommentUpdated' => [
			'App\Listeners\Comment\ClearMotionCommentCache',
		],
		'App\Events\CommentVoteCreated' => [
			'App\Listeners\Comment\ClearMotionCommentCache',
		],
		'App\Events\CommentVoteUpdated' => [
			'App\Listeners\Comment\ClearMotionCommentCache',
		],
		'App\Events\CommentVoteDeleted' => [
			'App\Listeners\Comment\ClearMotionCommentCache',
		],
		'App\Events\SendPasswordReset' => [
			'App\Listeners\User\SetRememberToken',
			'App\Listeners\User\SetRandomPassword',
			'App\Listeners\User\SendResetEmail'	
		],
		'App\Events\SendDailyEmails' => [
			'App\Listeners\Motion\SendDailyPublicMotionSummary',
			'App\Listeners\User\SendDailyAdminUserSummary'
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
