<?php

namespace App\Listeners\User\Creating;

use App\Events\User\UserCreating;

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
        // Authentication and Users
        $event->user->setPreference('authentication.notify.admin.oncreate', 1, true); // Used in User Model Event
        $event->user->setPreference('authentication.notify.admin.summary', 1, true); // Used in Jobs
        $event->user->setPreference('authentication.notify.user.onrolechange', 1, true);

        // Motions
        $event->user->setPreference('motion.notify.user.onchange', 0, true);
        $event->user->setPreference('motion.notify.user.summary', 0, true); // Used in Jobs
        $event->user->setPreference('motion.notify.admin.summary', 0, true); // TODO: not used

        return true;
    }
}
