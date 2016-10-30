<?php

namespace App\Listeners\User\Deleting;

use App\Events\User\UserDeleting;
use App\Vote;

class DeleteVotesOnNonclosedMotions
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
     * We need to keep a record of the votes that ended up counting,
     * but we can remove users who never did anything (never got approved etc).
     *
     * @param UserDeleting $event
     *
     * @return void
     */
    public function handle(UserDeleting $event)
    {
        $user = $event->user;

        $activeMotionVotes = Vote::motionStatus(['draft', 'review', 'published'])->where('user_id', $user->id)->get();

        foreach ($activeMotionVotes as $activeMotionVote) {
            $activeMotionVote->delete();
        }
    }
}
