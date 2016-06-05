<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdministratorCommentTest extends TestCase
{
    use DatabaseTransactions;    
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
        $this->user->addUserRoleByName('administrator');
    }

    /*****************************************************************
    *
    *                   Basic CRUD functions:
    *
    ******************************************************************/

    /** @test */
    public function changing_vote_shows_changed_comments()
    {
        $vote = factory(App\Vote::class)->create([
            'user_id'   =>  $this->user->id,
            'position'  =>  1
        ]);

        $comment = factory(App\Comment::class)->create([
            'vote_id'   =>  $vote->id  
        ]);

        $this->get('/api/motion/'.$vote->motion_id.'/comment')
            ->assertResponseStatus(200);

        $response = json_decode($this->response->getContent(),true);
            
        $this->assertEquals(count($response['agreeComments']),1);
        $this->assertEquals(count($response['disagreeComments']),0);

        $vote->position = -1;
        $vote->save();

        $this->get('/api/motion/'.$vote->motion_id.'/comment')
            ->assertResponseStatus(200);


        $response = json_decode($this->response->getContent(),true);
        $this->assertEquals(count($response['agreeComments']),0);
        $this->assertEquals(count($response['disagreeComments']),1);

    }


  
    /** @test */
    public function comment_index_permissions_working()
    {

        $comment = factory(App\Comment::class)->create();

        $this->get('/api/comment');
        
        $this->assertResponseStatus(200);

        $this->see($comment->text);
    }

    /** @test */
    public function it_can_see_another_users_comment()
    {
        $comment = factory(App\Comment::class)->create();

        $this->get('/api/comment/'.$comment->id);

        $this->assertResponseStatus(200);
        $this->seeJson([ 'id' => $comment->id, 'text' => $comment->text ]);
    }

    /** @test */
    public function it_can_create_a_comment()
    {

        postComment($this);

        $posted = $this->response->getOriginalContent()->toArray();

        $this->assertResponseStatus(200);

        $this->seeInDatabase('comments',['text' => $posted['text']]);

    }


}
