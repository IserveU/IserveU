<?php

namespace App\Listeners\User\Created;

use App\Events\User\UserCreated;
use App\Notifications\Authentication\Welcome;
use Auth;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrepareWelcomeEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param UserCreated $event
     *
     * @return void
     */
    public function handle(UserCreated $event)
    {
        /* Created by self */
        if (!Auth::check()) {
            $welcomeNotification = new Welcome($event->user, false);
            $event->user->notify($welcomeNotification);

            return true;
        }

        /* Created by an admin or other logged in user */
        $welcomeNotification = new Welcome($event->user, true);
        $event->user->notify($welcomeNotification);

        return true;
    }
}
