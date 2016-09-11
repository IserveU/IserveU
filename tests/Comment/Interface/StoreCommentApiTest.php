<?php
include_once('CommentApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreCommentApiTest extends CommentApi
{
   
    use DatabaseTransactions;

    protected $class                =   App\Comment::class;

    protected $modelToUpdate;

    public function setUp()
    {   
        parent::setUp();

        $this->signInAsRole('administrator');

        $this->vote         =   factory(App\Vote::class)->create([
            'user_id'   =>  $this->user->id
        ]);

        $this->route        =   "/api/vote/".$this->vote->id."/comment/";

    }

    
    /** @test  ******************/
    public function store_comment_with_text(){
        $this->storeFieldsGetSee(['text'],200);   
    }

    
    /** @test  ******************/
    public function store_comment_with_status(){
        $this->storeFieldsGetSee(['text','status'],200);   
    }


    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    
    /** @test  ******************/
    public function store_comment_with_motion_id_fails(){
        $motion = factory(App\Motion::class)->create();
        $this->storeContentGetSee([
            'text'          =>  "You cant store on a motion directly",
            'motion_id'     =>  $motion->id
        ],400);     
    }

    /** @test  ******************/
    public function store_comment_with_vote_id_fails(){
        $this->storeContentGetSee([
            'text'          =>  "The routes sets the vote",
            'vote_id'       =>  $this->vote->id
        ],400);     
    }


    /** @test  ******************/
    public function store_comment_with_no_text_fails(){
        $this->storeContentGetSee([
            'text'          =>    ""
        ],400); 

        $this->storeContentGetSee([
            'text'          =>    null
        ],400); 
    }




}
