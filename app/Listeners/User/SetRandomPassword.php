<?php

namespace App\Listeners\User;

class SetRandomPassword
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
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

        $randomPassword = str_random(50);

        $user->password = $randomPassword;

        $user->save(); //Yeah... this seems stupid
    }
}
