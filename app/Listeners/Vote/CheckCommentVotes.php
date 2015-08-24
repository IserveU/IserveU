<?php

namespace App\Listeners\Vote;

use App\Events\VoteUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\CommentVote;
use App\Comment;
use DB;

class CheckCommentVotes
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
     * @param  VoteUpdated  $event
     * @return void
     */
    public function handle(VoteUpdated $event)
    {
        $vote = $event->vote;

        $dirty = $vote->getDirty();

        if(!isset($dirty['position'])){ //Position hasn't been changed
            return true;
        }

        $original = $vote->getOriginal();

        $oldCommentVotes    =   CommentVote::where('vote_id',$vote->id)->onCommentsOfPosition($original['position'])->notUser($vote->user_id)->delete();

        $newCommentVotes    =   CommentVote::where('vote_id',$vote->id)->onCommentsOfPosition($vote['position'])->restore();

        

    //    $newCommentVotes    = CommentVote::onCommentsOfPosition($vote->position);//->restore();

        return true;
    }
}
