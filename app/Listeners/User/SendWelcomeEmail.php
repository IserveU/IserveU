<?php

namespace App\Listeners\User;

use App\Events\User\UserCreated;
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

        $data = array(
            'user'  =>  $user,
            'title' =>  'Welcome'
        );


        if($user->modificationTo->first()->modification_by_id == $user->id){ //If this created user created themselves


            Mail::send('emails.welcome',$data, function ($m) use ($user) {
                 $m->to($user->email, $user->first_name)->subject('Welcome To IserveU');
            });            
        } else { //This created user was made by another user
            Mail::send('emails.welcomecreated', $data, function ($m) use ($user) {
                 $m->to($user->email, $user->first_name)->subject('Welcome To IserveU');
            });
        }



    }
}
