<?php

namespace App\Listeners\Motion\Updated;

use App\Events\Motion\MotionUpdated;
use App\Motion;
use App\Notifications\AlteredMotion;
use App\Vote;

class AlertVoters
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
     * Handle the event. Wonder if this could all just be a daily email if the "Updated" field has changed.
     *
     * @param MotionUpdated $event
     *
     * @return void
     */
    public function handle(MotionUpdated $event)
    {
        $motion = $event->motion;

        if (!$this->hasAlteredLockedFields($motion)) {
            return true;
        }

        $motionVotes = Vote::whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        })->where('motion_id', $motion->id)->get();

        foreach ($motionVotes as $motionVote) {
            $motionVote->user->notify(new AlteredMotion($motion));
        }
    }

    public function hasAlteredLockedFields(Motion $motion)
    {
        $dirty = $motion->getDirty();

        if (array_key_exists('title', $dirty)) {
            return true;
        }
        if (array_key_exists('text', $dirty)) {
            return true;
        }

        return false;
    }
}
