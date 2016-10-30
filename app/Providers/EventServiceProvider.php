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
        'App\Events\User\UserCreated' => [
            'App\Listeners\User\Created\PrepareWelcomeEmail', // Tested
            'App\Listeners\User\Created\PrepareUserCreatedEmail',  //Tested
        ],
        'App\Events\User\UserCreating' => [
            'App\Listeners\User\Creating\SetRememberToken', //Tested
            'App\Listeners\User\Creating\SetApiToken', //Tested
            'App\Listeners\User\Creating\SetDefaultPreferences', //Tested
        ],
        'App\Events\User\UserDeleted' => [
            'App\Listeners\User\Deleted\HardDeleteEmptyUser', // Tested
        ],
        'App\Events\User\UserDeleting' => [
            'App\Listeners\User\Deleting\DeleteVotesOnNonclosedMotions', //Tested
            'App\Listeners\User\Deleting\DeleteNonclosedMotions', //Tested
        ],
        'App\Events\User\UserUpdated'    => [
            'App\Listeners\User\Updated\DeleteUnattachedFiles', //Tested
            'App\Listeners\User\Updated\CheckUserRoles', //Tested
        ],
        'App\Events\User\UserUpdating'    => [
            'App\Listeners\User\Updating\AddUserModificationEntry', //Tested
            'App\Listeners\User\Updating\IdentityReverification',  //Tested
        ],
        'App\Events\Motion\MotionUpdated' => [
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
