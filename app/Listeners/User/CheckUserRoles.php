<?php

namespace App\Listeners\User;

use App\Events\UserUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Carbon\Carbon;
use DB;

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
     * Handle the event.
     *
     * @param  UserUpdated  $event
     * @return void
     */
    public function handle($event)
    {

       // DB::enableQueryLog();
        $user = $event->user;
        $user->load('roles');
        if($user->hasRole('citizen')){
            if(!$user->identity_verified || $user->address_verified_until['carbon']->lt(Carbon::now())){
                $user->removeUserRoleByName('citizen');
                $user->delegatedTo->delete();
                $user->delegatedFrom->delete();

            }
         
            return true;
        } else if($user->identity_verified && $user->address_verified_until['carbon']->gt(Carbon::now())) {
            $user->addUserRoleByName('citizen');
            $user->createDefaultDelegations();
            
            return true;
        }


      //  print_r(DB::getQueryLog());
    }
}
