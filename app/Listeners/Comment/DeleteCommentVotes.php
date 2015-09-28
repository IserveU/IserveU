<?php

namespace App\Listeners\Comment;

use App\Events\CommentDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param  CommentDeleted  $event
     * @return void
     */
    public function handle(CommentDeleted $event)
    {
        $event->comment->commentVotes()->forceDelete();
    }
}
