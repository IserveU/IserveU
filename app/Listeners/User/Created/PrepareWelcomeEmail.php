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
        $welcomeNotification = new Welcome();

        if (Auth::check()) {
            $welcomeNotification->createdByOther = true;
        }

        $event->user->notify($welcomeNotification);
    }
}
