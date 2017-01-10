<?php

namespace App\Listeners\User\Creating;

use App\Events\User\UserCreating;
use App\Repositories\Preferences\PreferenceMananger;

class SetDefaultPreferences
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
        (new PreferenceMananger($event->user))->setDefaults();

        return true;
    }
}
