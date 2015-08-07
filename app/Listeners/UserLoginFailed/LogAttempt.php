<?php

namespace App\Listeners\UserLoginFailed;

use App\Events\UserLoginFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

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

        $event->user->login_attempts++;
        if($event->user->login_attempts > 5 && $event->user->locked_until!=null){
            $event->user->locked_until = Carbon::now()->addHours(3);
        }
        $event->user->save();
    }
}