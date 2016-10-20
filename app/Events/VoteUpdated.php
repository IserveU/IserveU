<?php

namespace App\Events;

use App\Vote;
use Illuminate\Queue\SerializesModels;

class VoteUpdated extends Event
{
    use SerializesModels;

    public $vote;
    public $motion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Vote $vote)
    {
        $this->vote = $vote;
        $this->motion = $vote->motion;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
