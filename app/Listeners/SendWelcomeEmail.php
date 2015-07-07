<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail;


class SendWelcomeEmail implements ShouldQueue
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
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event) 
    { 
        $user = $event->user;

        Mail::send('emails.welcome', ['user' => $user], function ($m) use ($user) {
            $m->to($user->email, $user->first_name)->subject('Welcome To IserveU');
        });
    }
}
