<?php

namespace App\Listeners\Vote;

use App\Events\VoteCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Vote;

class SetDeferedToVotes
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
     * A funciton that usually runs recursively up the vote chain
     *
     * @param  VoteCreated  $event
     * @return void
     */
    public function handle($event)
    {
        $vote = $event->vote;

        $deferredVotes = Vote::where('deferred_to_id',$vote->user_id)->get();

        foreach($deferredVotes as $deferredVote){
            $deferredVote->position = $vote->position;
            $deferredVote->save();
        }

    }
}
