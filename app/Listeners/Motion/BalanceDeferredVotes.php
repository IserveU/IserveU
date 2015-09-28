<?php

namespace App\Listeners\Motion;

use App\Events\VoteCreated;
use App\Events\VoteUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Motion;
use App\Vote;

class BalanceDeferredVotes
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
     * @param  VoteCreated  $event
     * @return void
     */
    public function handle($event)
    {
       $motion = $event->motion;

       $votes = $motion->votes->groupBy('deferred_to_id')->toArray();
       array_multisort(array_map('count', $votes), SORT_DESC, $votes);

       if(count(head($votes))-count(last($votes))>1){
            $toMoveId = head($votes)[0]['id'];
            $toMove = Vote::find($toMoveId);
            $toDeferToId = last($votes)[0]['deferred_to_id'];
            $toMove->deferred_to_id = $toDeferToId;
            $toMove->save();
       }

    }
}
