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
       // \DB::enableQueryLog();
        //DB::enableQueryLogging();
       // \DB::table('votes')->where('deferred_to_id',$vote->user_id)->where('motion_id',$vote->motion_id)->update(['position'=>$vote->position]);
        $deferredVotes = Vote::where('deferred_to_id',$vote->user_id)->where('motion_id',$vote->motion_id)->get(); //update(['position'=>$vote->position]);
      //  echo print_r(\DB::getQueryLog());

        foreach($deferredVotes as $deferredVote){
            // echo "\br HERE ".$deferredVote;
            $deferredVote->position = $vote->position;
            $deferredVote->save();
        }

    }
}
