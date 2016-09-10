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
		'App\Events\User\UserUpdating'	=> [	//Things that might trigger a save on the user model
			'App\Listeners\User\IdentityReverification',
		],
		'App\Events\User\UserUpdated'	=> [
			'App\Listeners\User\AddUserModificationEntry',
			'App\Listeners\User\DeleteUnattachedFiles',
			'App\Listeners\User\CheckUserRoles', //for some reason this is being fired on create and conflicting with processes
		],
		'App\Events\User\UserCreating' => [ //Things that "Save" the user model should go in here
			'App\Listeners\User\SetRememberToken'
		],
		'App\Events\User\UserCreated' => [ //Things that save other records should go here
			'App\Listeners\User\AddUserModificationEntry',
			'App\Listeners\User\SendWelcomeEmail',
		//	'App\Listeners\User\CreateDefaultDelegations'
		],
		'App\Events\User\UserLoginFailed' => [
			'App\Listeners\User\LogAttempt', // Also locks accounts
			'App\Listeners\User\SetRememberToken', //Doesn't reset password
			'App\Listeners\User\SendAccountLockEmail',
		],
		'App\Events\User\UserLoginSucceeded' => [
			'App\Listeners\User\ClearLockFields',
		],
		'App\Events\User\UserDeleted' => [
			'App\Listeners\User\DeleteUser',
			'App\Listeners\User\DeleteActiveVotes'
		],
		'App\Events\Motion\MotionUpdated' => [ //Added notes on what this does to model
			'App\Listeners\Motion\SendNotificationEmail',
			'App\Listeners\Motion\AlertVoters'
		],
		'App\Events\VoteUpdated' => [
			'App\Listeners\Vote\CheckCommentVotes',
			'App\Listeners\Comment\ClearMotionCommentCache',
		],
		'App\Events\Motion\MotionCreated' => [ //Added notes on what this does to model
	
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
		],
		'App\Events\Setup\Initialize' => [
            'App\Listeners\Setup\SetDefaultSettings',
            'App\Listeners\Setup\SetDefaultPermissions',
            'App\Listeners\Setup\SetAdminUser',
            'App\Listeners\Setup\RunDBSeeder'
        ],
        'App\Events\Setup\Defaults' => [
            'App\Listeners\Setup\SetDefaultSettings',
            'App\Listeners\Setup\SetDefaultPermissions'
        ],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot()
	{
		parent::boot();
	}

}
