<?php

namespace App\Listeners\User\Deleted;

use App\Events\User\UserDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteActiveVotes implements ShouldQueue
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

        $activeMotionVotes = Vote::onActiveMotion()->where('user_id', $user->id)->get();

        foreach ($activeMotionVotes as $activeMotionVote) {
            $activeMotionVote->delete();
        }
    }
}
