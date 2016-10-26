<?php

namespace App\Listeners\User\Created;

use App\Events\User\UserCreated;
use App\Notifications\Welcome;
use Auth;

class PrepareWelcomeEmail
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
        $welcomeNotification = new Welcome();

        if (Auth::check()) {
            $welcomeNotification->createdByOther = true;
        }

        $event->user->notify($welcomeNotification);
    }
}
