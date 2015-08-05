<?php

namespace App\Listeners\UserLoginFailed;

use App\Events\UserLoginFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendResetEmail
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
        if(!$event->user){ //This email address wasn't associated with a user
            Mail::send('emails.unknownemail',['event' => $event], function ($m) use ($event) {
                 $m->to($event->credentials->email, 'Unregistered Email Account')->subject('IserveU Login Attempts');
            });
        }
    }
}
