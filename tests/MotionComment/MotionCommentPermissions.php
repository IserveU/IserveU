<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionCommentsPermissionTest extends TestCase
{
    use DatabaseTransactions;    
    use WithoutMiddleware;

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
    

    /** @test  just putting this here for now*/ 
    public function it_can_get_motion_comments(){
        $motion = factory(App\Motion::class,'published')->create();

        $thisUsersVote = factory(App\Vote::class)->create([
            'motion_id' => $motion->id
        ]);

        $thisUsersComment = factory(App\Comment::class)->create([
            'vote_id' => $thisUsersVote->id
        ]);

        $positiveVote = factory(App\Vote::class)->create([
            'motion_id' => $motion->id,
            'position'  => 1
        ]);

        $positiveComment = factory(App\Comment::class)->create([
            'vote_id'   =>  $positiveVote->id
        ]);

        $negativeVote = factory(App\Vote::class)->create([
            'motion_id' => $motion->id,
            'position'  => -1
        ]);

        $negativeComment = factory(App\Comment::class)->create([
            'vote_id'   =>  $negativeVote->id
        ]);

        $abstainVote = factory(App\Vote::class)->create([
            'motion_id' => $motion->id,
            'position'  => 0
        ]);
        
        $abstainComment = factory(App\Comment::class)->create([
            'vote_id'   =>  $abstainVote->id
        ]);

        $this->get('/api/motion/'.$motion->id.'/comment');

        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'agreeComments' => [
                '*' =>  ['id','text']
            ],
            'disagreeComments' => [
                '*' =>  ['id','text']
            ],
            'thisUsersComment',
            'thisUsersCommentVotes'
        ]);
    

        $this->response->getContent();
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
        $comment = postComment($this);

        $this->seeInDatabase('comments',[
            'id'    => $comment->id,
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

        $this->assertEquals(401, $response->status());
    }


    /** @test */
    public function it_can_update_comment()
    {
        $comment = postComment($this);
        // Update comment
        $new_comment = factory(App\Comment::class)->make(['id' => $comment->id])->toArray();

        $this->call('PATCH', '/api/comment/'.$comment->id, $new_comment);
        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'id' => $comment->id, 'text' => $new_comment['text'] ]);
    }


        /** @test */
    public function it_can_delete_comment()
    {
        $comment = postComment($this);
        
        // Delete comment
        $delete = $this->call('DELETE', '/api/comment/'.$comment->id);
        $delete = $delete->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', ['deleted_at' => $delete->deleted_at]);
    }




}
