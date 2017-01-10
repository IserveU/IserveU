<?php

namespace App\Listeners\User\Creating;

use App\Events\User\UserCreating;
use App\Repositories\Preferences\PreferenceManager;

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
        (new PreferenceManager($event->user))->setDefaults();

        return true;
    }
}
