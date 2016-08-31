<?php

namespace App\Listeners\User;

use App\Events\User\UserUpdated;
use App\UserModification;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Auth;

class AddUserModificationEntry
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
     * @param  UserUpdated  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        $modifiedRecord = new UserModification;
        
        if(Auth::check()){
            $modifiedRecord->modification_by_id = Auth::user()->id;
        } else {
            $modifiedRecord->modification_by_id = $user->id; //Registered themselves
        }
     
        $modifiedRecord->modification_to_id = $user->id;
        $modifiedRecord->fields = json_encode($user->getDirty());
        $modifiedRecord->save();
    }
}
