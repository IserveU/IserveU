<?php

namespace App\Listeners\User;

use App\Events\UserLoginFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Hash;
use Mail;

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
    public function handle($event)
    {
        if(!$event->user){ //This email address wasn't associated with a user
            abort(404,"this email address does not exist");
        }

        $user = $event->user;

        $data = array(
            'user'      =>      $user,
            'title'     =>      "Password Reset"
        );

        Mail::send('emails.passwordreset',$data, function ($m) use ($user) {
            $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Trouble Logging In?');
        });
        
        return 'password reset sent';
    }
}
