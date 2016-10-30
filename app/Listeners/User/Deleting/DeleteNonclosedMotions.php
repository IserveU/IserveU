<?php

namespace App\Listeners\User\Deleting;

use App\Events\User\UserDeleting;
use App\Motion;

class DeleteNonclosedMotions
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
        $motions = Motion::status(['draft', 'review', 'published'])->writer($event->user)->get();

        foreach ($motions as $motion) {
            $motion->forceDelete(); //Todo: Setup soft deleting on motion with votes, (setup motion delete events to check for votes) and then on the user permanent delete look for soft deleted objects too
        }
    }
}
