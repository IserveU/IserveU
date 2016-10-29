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
        'App\Events\User\UserUpdating'    => [
            'App\Listeners\User\Updating\IdentityReverification',
        ],
        'App\Events\User\UserUpdated'    => [
            'App\Listeners\User\Updated\BroadcastToRedis',
            'App\Listeners\User\Updated\AddUserModificationEntry',
            'App\Listeners\User\Updated\DeleteUnattachedFiles',
            'App\Listeners\User\Updated\CheckUserRoles',
        ],
        'App\Events\User\UserCreating' => [
            'App\Listeners\User\Creating\SetRememberToken',
            'App\Listeners\User\Creating\SetApiToken',
            'App\Listeners\User\Creating\SetDefaultPreferences',
        ],
        'App\Events\User\UserCreated' => [
            'App\Listeners\User\Created\PrepareWelcomeEmail',
            'App\Listeners\User\Created\PrepareUserCreatedEmail',
        ],
        'App\Events\User\UserDeleted' => [
            'App\Listeners\User\Deleted\DeleteEmptyUserRecords',
            'App\Listeners\User\Deleted\DeleteActiveVotes',
        ],
        'App\Events\Motion\MotionUpdated' => [
            'App\Listeners\Motion\Updated\SendNotificationEmail',
            'App\Listeners\Motion\Updated\AlertVoters',
        ],
        'App\Events\Motion\MotionCreated' => [

        ],
        'App\Events\Vote\VoteUpdated' => [
            'App\Listeners\Vote\Updated\CheckCommentVotes',
        ],
        'App\Events\Comment\CommentDeleted' => [
            'App\Listeners\Comment\Deleted\DeleteCommentVotes',
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
