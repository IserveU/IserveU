<?php

namespace App\Listeners\User;

use Mail;
use Auth;
use App\Events\User\UserUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IdentityReverification{

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
     * @param  UserUpdatedProfile  $event
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        $user = $event->user;
        if(Auth::check() && Auth::user()->can('administrate-user')){ //Admins don't need to
           return true;
        }

        $changedFields = $user->getAlteredLockedFields();

        if(!empty($changedFields) && $user->identity_verified){
           $user->identity_verified = 0;
        
           Mail::send('emails.reverification', ['user' => $user], function ($m) use ($user) {
            $m->to($user->email, $user->first_name)->subject('Reverification Required');
           });
        }
        return true;

    }
}
