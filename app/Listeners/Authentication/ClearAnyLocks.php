<?php

namespace App\Listeners\Authentication;

use App\Events\Authentication\UserLoginSucceeded;

class ClearAnyLocks
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
     * Clears the signs of the account being locked.
     *
     * @param UserLoginSucceeded $event
     *
     * @return void
     */
    public function handle(UserLoginSucceeded $event)
    {
        if ($event->user->login_attempts == 0 && $event->user->locked_until == null) {
            return true;
        }
        $event->user->login_attempts = 0;
        $event->user->locked_until = null;
        $event->user->save();

        return true;
    }
}
