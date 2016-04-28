<?php

namespace App\Listeners\User;

use App\Events\User\UserLoginFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearLockFields
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

        $user->remember_token   = null;
        $user->login_attempts   = 0;
        $user->locked_until     = null;        

        if(!$user->save()){ //Validation failed show errors
            abort(403,$event->user->errors);
        }
        
    }
}
