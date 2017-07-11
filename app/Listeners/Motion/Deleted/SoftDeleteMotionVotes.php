<?php

namespace App\Listeners\Motion\Deleted;

use App\Events\Motion\MotionDeleted;

class SoftDeleteMotionVotes
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
     * @param MotionDeleted $event
     *
     * @return void
     */
    public function handle(MotionDeleted $event)
    {
        $motion = $event->motion;

        foreach ($motion->votes as $vote) {
            $vote->delete();
        }
    }
}
