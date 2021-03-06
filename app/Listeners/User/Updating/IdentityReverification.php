<?php

namespace App\Listeners\User\Updating;

use App\Notifications\Authentication\IdentityReverification as IdentityReverificationNotification;
use App\User;
use Auth;

class IdentityReverification
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
     * @param UserUpdatedProfile $event
     *
     * @return void
     */
    public function handle($event)
    {
        if (Auth::check() && Auth::user()->can('administrate-user')) { //Admins don't need to
            return true;
        }

        if (!$this->changedCriticialIdentityFields($event->user)) {
            return true;
        }

        if ($event->user->identity_verified) {
            $event->user->notify(new IdentityReverificationNotification());

            $event->user->identity_verified = 0;
        }

        return true;
    }

    public function changedCriticialIdentityFields(User $user)
    {
        $dirty = $user->getDirty();
        $requiresReverification = ['first_name', 'last_name', 'date_of_birth'];
        foreach ($requiresReverification as $key) {
            if (array_key_exists($key, $dirty)) {
                return true;
            }
        }

        return false;
    }
}
