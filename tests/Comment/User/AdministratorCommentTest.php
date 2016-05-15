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
