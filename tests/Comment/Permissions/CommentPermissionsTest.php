<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentPermissionTest extends TestCase
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /*****************************************************************
    *
    *                   Basic CRUD functions:
    *
    ******************************************************************/
    

    /** @test */
    public function comment_index_permissions_working()
    {

        $comment = factory(App\Comment::class)->create();

        $this->get('/api/comment');

        $this->assertResponseStatus(200);

        $this->see($comment->text);
    }


    /** @test */
    public function it_can_see_comments_made_on_the_motion()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $this->call('GET', '/api/motion/'.$motion->id.'/comment');

        $this->assertResponseOk();
    }

    /** @test */ 
    public function it_can_create_a_comment()
    {
        $this->signInAsPermissionedUser('create-comment');

        $comment = postComment($this);


        $this->seeInDatabase('comments',[
            'text'  => $comment->text
        ]);
    }


    /** @test */
    public function it_cannot_create_a_comment()
    {

        $faker = Faker\Factory::create();

        $motion = factory(App\Motion::class, 'published')->create();

        $comment = ['vote_id'  => 1, 
                    'motion_id' => $motion->id];

        $response = $this->call('POST', '/api/comment', $comment);

        $this->assertEquals(403, $response->status());
    }


    /** @test */
    public function it_can_update_comment()
    {
        $this->signInAsPermissionedUser('create-comment');

        $comment = factory(App\Comment::class)->create();
        $comment->vote->user_id = $this->user->id;
        $comment->vote->save();

        // Update comment
        $updatedComment = [
            'text' => "updated text for the comment"
        ];

        $this->patch('/api/comment/'.$comment->id, $updatedComment);
        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'id' => $comment->id, 'text' => $updatedComment['text'] ]);
    }


      /** @test */
    public function it_cannot_update_others_comment()
    {
        $this->signInAsPermissionedUser('create-comment');

        $comment = factory(App\Comment::class)->create();

        // Update comment
        $updatedComment = [
            'text' => "updated text for the comment"
        ];

        $this->patch('/api/comment/'.$comment->id, $updatedComment);
        $this->assertResponseStatus(403);
        $this->dontSeeInDatabase('comments', [ 'id' => $comment->id, 'text' => $updatedComment['text'] ]);
    }


    /** @test */
    public function it_can_delete_comment()
    {
        $this->signInAsPermissionedUser('create-comment');
        $comment = factory(App\Comment::class)->create();
        $comment->vote->user_id = $this->user->id;
        $comment->vote->save();

        // Delete comment
        $this->delete('/api/comment/'.$comment->id);
        
        $this->assertResponseOk();
        $this->dontSeeInDatabase('comments', ['id'=>$comment->id,'deleted_at' => null]);
    }


        /** @test */
    public function it_can_delete_others_comment()
    {
        $this->signInAsPermissionedUser('delete-comment');
        $comment = factory(App\Comment::class)->create();


        // Delete comment
        $this->delete('/api/comment/'.$comment->id);
        
        $this->assertResponseOk();
        $this->dontSeeInDatabase('comments', ['id'=>$comment->id,'deleted_at' => null]);
    }

    /** @test */
    public function it_cannot_delete_others_comment()
    {
        $this->signInAsPermissionedUser('create-comment');
        $comment = factory(App\Comment::class)->create();


        // Delete comment
        $this->delete('/api/comment/'.$comment->id);
        
        $this->assertResponseStatus(403);
        $this->seeInDatabase('comments', ['id'=>$comment->id,'deleted_at' => null]);
    }



    /** @test */
    public function it_can_see_another_users_comment()
    {
        $comment = factory(App\Comment::class)->create();

        $this->get('/api/comment/'.$comment->id);

        $this->assertResponseStatus(200);

        $this->seeJson([ 'id' => $comment->id, 'text' => $comment->text ]);
    }


}
