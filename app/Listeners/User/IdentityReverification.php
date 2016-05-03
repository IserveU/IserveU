<?php

namespace App\Listeners\User;

use Mail;
use Auth;
use App\Events\User\UserUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

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
        if(Auth::check() && Auth::user()->can('administrate-user')){ //Admins don't need to
           return true;
        }

        if(!$this->changedCriticialIdentityFields($event->user)){
            return true;
        }
        
   
        
        if($event->user->identity_verified){
            Mail::send('emails.reverification', ['user' => $event->user], function ($m) use ($event) {

                $m->to($event->user->email, $event->user->first_name)->subject('Reverification Required');

            });

            $event->user->identity_verified = 0;
        }

        return true;

    }

    public function changedCriticialIdentityFields(User $user){
        $dirty = $user->getDirty();
        $requiresReverification = array('first_name','last_name','date_of_birth');
        foreach($requiresReverification as $key){
            if(array_key_exists($key,$dirty)){
                return true;
            }
        }
        return false;

    }
}
