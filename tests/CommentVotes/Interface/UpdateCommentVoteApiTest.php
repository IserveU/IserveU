<?php
include_once('CommentVoteApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateCommentVoteApiTest extends commentvoteApi
{

    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();
        $this->setSettings(['security.verify_citizens',false]);

        $this->modelToUpdate = factory(App\CommentVote::class)->create();

        $this->signIn($this->modelToUpdate->vote->user);

        $this->route        =   "/api/comment_vote/";
    }


    /** @test  ******************/
    public function update_commentvote_with_text(){
        $this->updateFieldsGetSee(['position'],200);
    }


    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    
    /** @test  ******************/
    public function update_commentvote_with_motion_id_fails(){

        $this->updateContentGetSee([
            'position'      =>  1,
            'motion_id'     =>  $this->modelToUpdate->vote->motion_id
        ],400);     
    }

    /** @test  ******************/
    public function update_commentvote_with_vote_id_fails(){
        $this->updateContentGetSee([
            'position'      =>  -1,
            'vote_id'       =>  $this->modelToUpdate->vote->id
        ],400);     
    }


    /** @test  ******************/
    public function update_commentvote_with_no_text_fails(){
        $this->updateContentGetSee([
            'position'      =>    ""
        ],400);

        $this->updateContentGetSee([
            'position'      =>    null
        ],400);   
    }

}