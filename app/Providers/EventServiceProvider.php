<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\User\UserUpdating'    => [    //Things that might trigger a save on the user model
            'App\Listeners\User\Updating\IdentityReverification',
        ],
        'App\Events\User\UserUpdated'    => [
            'App\Listeners\User\Updated\BroadcastToRedis',
            'App\Listeners\User\Updated\AddUserModificationEntry',
            'App\Listeners\User\Updated\DeleteUnattachedFiles',
            'App\Listeners\User\Updated\CheckUserRoles',
        ],
        'App\Events\User\UserCreated' => [ //Things that save other records should go here
            'App\Listeners\User\Created\PrepareWelcomeEmail',
        ],
        'App\Events\User\UserDeleted' => [
            'App\Listeners\User\DeleteUser',
            'App\Listeners\User\DeleteActiveVotes',
        ],
        'App\Events\Motion\MotionUpdated' => [ //Added notes on what this does to model
            'App\Listeners\Motion\SendNotificationEmail',
            'App\Listeners\Motion\AlertVoters',
        ],
        'App\Events\Motion\MotionCreated' => [//Added notes on what this does to model

        ],
        'App\Events\Vote\VoteUpdated' => [
            'App\Listeners\Vote\CheckCommentVotes',
        ],
        'App\Events\Comment\CommentDeleted' => [
            'App\Listeners\Comment\DeleteCommentVotes',
        ],

        //Move Out
        'App\Events\User\UserLoginFailed' => [
            'App\Listeners\User\LogAttempt', // Also locks accounts
            'App\Listeners\User\SendAccountLockEmail',
        ],
        'App\Events\User\UserLoginSucceeded' => [
            'App\Listeners\User\ClearLockFields',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        User::observe(UserObserver::class);
    }
}
