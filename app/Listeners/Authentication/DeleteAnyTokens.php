<?php

namespace App\Listeners\Authentication;

use App\Events\Authentication\UserLoginSucceeded;
use App\OneTimeToken;

class DeleteAnyTokens
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
     * Handle the event.
     *
     * @param UserLoginSucceeded $event
     *
     * @return void
     */
    public function handle(UserLoginSucceeded $event)
    {
        OneTimeToken::for($event->user)->delete();
    }
}
