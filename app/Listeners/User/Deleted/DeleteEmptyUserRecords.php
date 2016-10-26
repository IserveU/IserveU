<?php

namespace App\Listeners\User\Deleted;

use App\Events\User\UserDeleted;
use DB;

class DeleteEmptyUserRecords
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
     * @param UserDeleted $event
     *
     * @return void
     */
    public function handle(UserDeleted $event)
    {
        $user = $event->user;

        $votes = $user->votes;
        $motions = $user->motions;

        //dd($user->modificationTo);
        if ($votes->isEmpty() && $motions->isEmpty()) {
            DB::table('user_modifications')->where('modification_to_id', $user->id)->delete();
            DB::table('users')->where('id', $user->id)->delete();
        }
    }
}
