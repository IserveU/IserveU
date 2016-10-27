<?php

namespace App\Listeners\User\Creating;

use App\Events\User\UserCreating;

class SetApiToken
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
     * @param UserCreating $event
     *
     * @return void
     */
    public function handle(UserCreating $event)
    {
        $event->user->api_token = str_random(99);
    }
}
