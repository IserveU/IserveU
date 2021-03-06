<?php

namespace App\Listeners\Comment\Deleted;

use App\Events\Comment\CommentDeleted;

class DeleteCommentVotes
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
     * @param CommentDeleted $event
     *
     * @return void
     */
    public function handle(CommentDeleted $event)
    {
        $event->comment->commentVotes()->forceDelete();
    }
}
