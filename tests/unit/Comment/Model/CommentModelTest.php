<?php

use App\Events\Comment\CommentCreated;
use App\Events\Comment\CommentDeleted;
use App\Events\Comment\CommentUpdated;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test **/
    public function check_update_events()
    {
        $comment = factory(App\Comment::class)->create();

        $this->doesntExpectEvents(CommentCreated::class);
        $this->expectsEvents(CommentUpdated::class);
        $this->doesntExpectEvents(CommentDeleted::class);

        $comment->update(['text' => 'new text']);
    }

    /** @test **/
    public function check_delete_events()
    {
        $comment = factory(App\Comment::class)->create();
        $this->doesntExpectEvents(CommentCreated::class);
        $this->doesntExpectEvents(CommentUpdated::class);
        $this->expectsEvents(CommentDeleted::class);

        $comment->delete();
    }
}
