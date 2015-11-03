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

        if($original['position']<=0 && $dirty['position']<=0){ //Don't delete if changed from neutral to abstrain
        	return true;
        }

        $oldCommentVotes    =   CommentVote::where('vote_id',$vote->id)->onCommentsOfPosition($original['position'])->notUser($vote->user_id)->delete(); // Comment Votes Cast by this user the original way
        $newCommentVotes    =   CommentVote::where('vote_id',$vote->id)->onCommentsOfPosition($vote['position'])->restore(); //Comment Votes Cast by this user the new way

        if($vote->comment){
            //echo $original['position']; Can not figure this out
          //  DB::enableQueryLog();
            //$commentsToDelete = CommentVote::where('comment_id',$vote->comment->id)->onCommentsOfPosition($original['position'])->notUser($vote->user_id)->delete(); // Delete the comment votes of this User
           // return $commentsToDelete;
            CommentVote::where('comment_id',$vote->comment->id)->forceDelete(); // Delete the comment votes of this User
          //  print_r(DB::getQueryLog());
        }
  
        return true;
    }
}
