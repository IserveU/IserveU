<?php

namespace App\Listeners\User;

use App\Events\User\UserLoginFailed;

class SendResetEmail
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
     * @param UserLoginFailed $event
     *
     * @return void
     */
    public function handle($event)
    {
    }
}
