<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Events\Comment\CommentDeleted;

use App\Events\Comment\CommentUpdated;
use App\Events\Comment\CommentCreated;
use App\Comment;

class CommentModelTest extends TestCase
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();

    }


    /** @test **/
    public function check_creation_events(){
        $this->expectsEvents(CommentCreated::class);
        $this->doesntExpectEvents(CommentUpdated::class);
        $this->doesntExpectEvents(CommentDeleted::class);

        factory(App\Comment::class)->create();
    }
 
    /** @test **/
    public function check_update_events(){
        $comment = factory(App\Comment::class)->create();

		$this->doesntExpectEvents(CommentCreated::class);
        $this->expectsEvents(CommentUpdated::class);
        $this->doesntExpectEvents(CommentDeleted::class);

        $comment->update(['text' => 'new text']);

    }

    /** @test **/
    public function check_delete_events(){

        $comment = factory(App\Comment::class)->create();
		$this->doesntExpectEvents(CommentCreated::class);
        $this->doesntExpectEvents(CommentUpdated::class);
        $this->expectsEvents(CommentDeleted::class);

        $comment->delete();

    }

}
