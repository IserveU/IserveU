<?php

namespace App\Listeners\User;

use App\Events\User\UserUpdated;
use Carbon\Carbon;
use DB;
use Setting;

class CheckUserRoles
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
    public function handle($event)
    {

        //Don't check this if the system doesn't care about manual verification
        if (!Setting::get('security.verify_citizens')) {
            return true;
        }

       // DB::enableQueryLog();
        $user = $event->user;
        $user->load('roles');
        if ($user->hasRole('citizen')) {
            if (!$user->identity_verified //User is not verified
                 || $user->address_verified_until // Has verified until set
                 || $user->address_verified_until['carbon']->lt(Carbon::now())) { //Address is verified prior to this date
                $user->removeRole('citizen');

                if (count($user->delegatedTo)) {
                    $user->delegatedTo->delete();
                }

                if (count($user->delegatedFrom)) {
                    $user->delegatedFrom->delete();
                }
            }

            return true;
        } elseif ($user->identity_verified && $user->address_verified_until && $user->address_verified_until['carbon']->gt(Carbon::now())) {
            $user->addRole('citizen');
            $user->createDefaultDelegations();

            return true;
        }
    }
}
