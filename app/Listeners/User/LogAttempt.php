<?php

namespace App\Listeners\User;

use App\Events\User\UserLoginFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use Setting;

class LogAttempt
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
    public function handle(UserLoginFailed $event)
    {
        if(!$event->user){
            return "no user with this email address";
        }

        $event->user->login_attempts = $event->user->login_attempts + 1;
        if($event->user->login_attempts > Setting::get('security.login_attempts_lock',5)){
            $event->user->locked_until = Carbon::now()->addHours(3);
        }

        $event->user->save();

    }
}
