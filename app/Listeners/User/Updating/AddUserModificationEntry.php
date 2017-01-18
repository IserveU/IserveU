<?php

namespace App\Listeners\User\Updating;

use App\Events\User\UserUpdating;
use App\UserModification;
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
     * @param UserUpdated $event
     *
     * @return void
     */
    public function handle(UserUpdating $event)
    {
        $user = $event->user;
        $modifiedRecord = new UserModification();

        if (Auth::check()) {
            $modifiedRecord->modification_by_id = Auth::user()->id;
        } else {
            $modifiedRecord->modification_by_id = $user->id; //Registered themselves
        }

        $modifiedRecord->modification_to_id = $user->id;
        $modifiedRecord->fields = json_encode($user->getDirty());
        $modifiedRecord->save();

        return true;
    }
}
