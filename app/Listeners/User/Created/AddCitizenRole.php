<?php

namespace App\Listeners\User\Created;

use App\Events\User\UserCreated;
use Setting;

class AddCitizenRole
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
        if (!Setting::get('security.verify_citizens')) {
            $event->user->addRole('citizen');
        }
    }
}
