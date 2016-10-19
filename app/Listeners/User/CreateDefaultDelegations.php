<?php

namespace App\Listeners\User;

use App\Events\User\UserCreated;
use App\User;

class CreateDefaultDelegations
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
     * @param UserCreated $event
     *
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $user->createDefaultDelegations();
    }
}
