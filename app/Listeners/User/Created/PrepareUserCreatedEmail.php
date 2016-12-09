<?php

namespace App\Listeners\User\Created;

use App\Events\User\UserCreated;
use App\Notifications\Authentication\UserCreated as UserCreatedNotification;
use App\User;

class PrepareUserCreatedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Notify admin when user has been created.
     *
     * @param UserCreated $event
     *
     * @return void
     */
    public function handle(UserCreated $event)
    {

        // For all admins, send a notification
        $admins = User::hasPermissions(['show-user'])->preference('authentication.notify.admin.oncreate', 1)->get();

        foreach ($admins as $admin) {
            $admin->notify(new UserCreatedNotification($event->user));
        }

        return true;
    }
}
