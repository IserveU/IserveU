<?php

namespace App\Listeners\Vote;

use App\Events\VoteDeleting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteVoteComment
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
     * @param  VoteDeleting  $event
     * @return void
     */
    public function handle(VoteDeleting $event)
    {
        $event->vote->comment->delete();
    }
}
