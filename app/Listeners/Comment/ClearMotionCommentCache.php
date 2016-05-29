<?php

namespace App\Listeners\Comment;
// namespace App\Listeners\Vote;


use App\Events\CommentCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cache;

class ClearMotionCommentCache
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
     * @param  CommentCreated  $event
     * @return void
     */
    public function handle($event)
    {
        $vote = $event->vote;
        if($vote){
            Cache::forget('motion'.$vote->motion_id.'_comments');
        }
    }
}
