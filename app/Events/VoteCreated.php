<?php

namespace App\Events;

use App\Events\Event;
use App\Vote;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class VoteCreated extends Event
{
    use SerializesModels;

    public $vote;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Vote $vote)
    {
        $this->vote     = $vote;
        $this->motion   = $vote->motion;        
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
