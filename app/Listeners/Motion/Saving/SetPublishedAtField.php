<?php

namespace App\Listeners\Motion\Saving;

use App\Events\Motion\MotionSaving;
use Carbon\Carbon;

class SetPublishedAtField
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
     * @param MotionSaving $event
     *
     * @return void
     */
    public function handle(MotionSaving $event)
    {
        if ($event->motion->status != 'published') {
            return true;
        }

        $changed = $event->motion->getOriginal();

        //If no status, published now
        if (!array_key_exists('status', $changed)) {
            $event->motion->published_at = Carbon::now();

            return true;
        }

        //If old status is not published, published now
        if ($changed['status'] != 'published') {
            $event->motion->published_at = Carbon::now();
        }

        return true;
    }
}
