<?php

namespace App\Listeners\User;

use App\Events\User\UserCreated;
use Mail;
use Setting;

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
     * @param UserRegistered $event
     *
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $data = [
            'user'                  => $user,
            'created_by_other'      => true,
        ];

        //If this created user created themselves
        if ($user->modificationTo->first()->modification_by_id == $user->id) {
            $data['created_by_other'] = false;
        }

        Mail::send('emails.welcome', ['data' => $data], function ($m) use ($user) {
            $m->to($user->email, $user->first_name)
             ->subject('Welcome To '.Setting::get('site.name', 'IserveU'));
        });
    }
}
