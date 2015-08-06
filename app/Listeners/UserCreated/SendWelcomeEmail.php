<?php

namespace App\Listeners\UserCreated;

use App\Events\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail;


class SendWelcomeEmail
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
    public function handle(UserCreated $event) 
    { 
        $user = $event->user;
        Mail::send('emails.welcome', ['user' => $user], function ($m) use ($user) {
             $m->to($user->email, $user->first_name)->subject('Welcome To IserveU');
        });
    }
}
