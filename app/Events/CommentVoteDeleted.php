<?php

namespace App\Events;

use App\CommentVote;
use Illuminate\Queue\SerializesModels;

class CommentVoteDeleted extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CommentVote $commentvote)
    {
        $this->vote = $commentvote->vote;
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
