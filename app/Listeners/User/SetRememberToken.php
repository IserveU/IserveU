<?php

namespace App\Listeners\User;

use App\User;
use App\Events\UserLoginFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Hash;
use Mail;

class SetRememberToken
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
     * @param  UserLoginFailed  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

            
        $hash = str_random(99);
        $user->remember_token = $hash;

        if(!$user->save()){ //Validation failed show errors
            abort(403,$event->user->errors);
        }          
     
    }
}
