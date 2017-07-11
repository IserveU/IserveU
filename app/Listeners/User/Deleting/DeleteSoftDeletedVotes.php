<?php

namespace App\Listeners\User\Deleting;

use App\Events\User\UserDeleting;
use App\Vote;

class DeleteSoftDeletedVotes
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
     * @param UserDeleting $event
     *
     * @return void
     */
    public function handle(UserDeleting $event)
    {
        $user = $event->user;

        $deletedVotes = Vote::onlyTrashed()->byUser($user)->get();

        foreach ($deletedVotes as $deletedVote) {
            $deletedVote->forceDelete();
        }
    }
}
