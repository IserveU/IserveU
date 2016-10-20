<?php

namespace App\Listeners\Motion;

use App\Events\Motion\MotionUpdated;

class SendNotificationEmail
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
     * @param MotionUpdated $event
     *
     * @return void
     */
    public function handle(MotionUpdated $event)
    {
        $motion = $event->motion;
    }
}
