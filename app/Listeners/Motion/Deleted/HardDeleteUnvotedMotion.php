<?php

namespace App\Listeners\Motion\Deleted;

use App\Events\Motion\MotionDeleted;
use DB;

class HardDeleteUnvotedMotion
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
        $votes = $motion->votes;

        //dd($user->modificationTo);
        if ($votes->isEmpty()) {
            DB::table('motions')->where('id', $motion->id)->delete(); //Force delete
        }
    }
}
