<?php

namespace App\Listeners\MotionUpdated;

use App\Vote;
use App\Events\MotionUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveVotes
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
     * @param  MotionUpdated  $event
     * @return void
     */
    public function handle(MotionUpdated $event)
    {
        $motion = $event->motion;

        $changedFields = $motion->getAlteredLockedFields();

        if(!empty($changedFields)){

            Vote::where('motion_id',$motion->id)->update(['position'=>0]);

        }

    }
}
