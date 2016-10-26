<?php

namespace App\Listeners\Vote;

use App\Comment;
use App\CommentVote;
use App\Events\Vote\VoteUpdated;

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
     * Update the comment votes cast by the user.
     *
     * @param VoteUpdated $event
     *
     * @return void
     */
    public function handle(VoteUpdated $event)
    {
        $vote = $event->vote;

        $dirty = $vote->getDirty();

        if (!isset($dirty['position'])) { //Position hasn't been changed
            return true;
        }

        $original = $vote->getOriginal();

        if ($original['position'] <= 0 && $dirty['position'] <= 0) { //Don't delete if changed from neutral to abstrain
            return true;
        }

        $oldCommentVotes = CommentVote::where('vote_id', $vote->id)->onCommentsOfPosition($original['position'])->notUser($vote->user_id)->delete(); // Comment Votes Cast by this user the original way
        $newCommentVotes = CommentVote::where('vote_id', $vote->id)->onCommentsOfPosition($vote['position'])->restore(); //Comment Votes Cast by this user the new way

        if ($vote->comment) {
            // Delete the comment votes on this users comments
            CommentVote::where('comment_id', $vote->comment->id)->forceDelete();
        }

        return true;
    }
}
