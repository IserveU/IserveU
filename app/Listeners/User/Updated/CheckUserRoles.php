<?php

namespace App\Listeners\User\Updated;

use App\Events\User\UserUpdated;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckUserRoles implements ShouldQueue
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
     * Checks that if the user is a citizen they have their address verified.
     * If their address isn't verified their citizenship is stripped.
     *
     * @param UserUpdated $event
     *
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        $event->user;
        $event->user->load('roles');

        if ($event->user->hasRole('citizen')) {
            if (!$event->user->identity_verified) {
                $this->stripCitizenRole($event);
            }

            if ($this->addressExpired($event)) {
                $this->stripCitizenRole($event);
            }

            return true;
        }


        if ($event->user->identity_verified && !$this->addressExpired($event)) {
            $event->user->addRole('citizen');
        }

        return true;
    }

    /**
     * Removes the citizen role and delegations.
     */
    public function stripCitizenRole(UserUpdated $event)
    {
        $event->user->removeRole('citizen');

        if (count($event->user->delegatedTo)) {
            $event->user->delegatedTo->delete();
        }

        if (count($event->user->delegatedFrom)) {
            $event->user->delegatedFrom->delete();
        }

        return true;
    }

    /**
     * Returns if the address is expired.
     */
    public function addressExpired(UserUpdated $event)
    {
        if (!$event->user->address_verified_until['carbon']) {
            return true;
        }

        if ($event->user->address_verified_until['carbon']->lt(Carbon::now())) { //Address is past
            return true;
        }

        return false;
    }
}
