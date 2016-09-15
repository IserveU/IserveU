<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexUserCommentVoteApiTest extends TestCase
{
    
    use DatabaseTransactions;    


    protected static $userCommentVoting;
 

    public function setUp()
    {
        parent::setUp();


        if(is_null(static::$userCommentVoting)){

            $motion = getStaticMotion();

            $vote   =   factory(App\Vote::class)->create();

            foreach($motion->comments as $comment){

                \App\CommentVote::create([
                    'comment_id'    =>  $comment->id,
                    'vote_id'       =>  $vote->id,
                    'position'      =>  rand(-1,1)
                ]);

            }

            static::$userCommentVoting = $vote->user;
        }

        $this->signIn(static::$userCommentVoting);
    }


    ///////////////////////////////////////////////////////////CORRECT RESPONSES 

    /** @test */
    public function default_user_comment_vote_filter(){

        $this->get('/api/user/'.static::$userCommentVoting->id."/comment_vote");
    }


    /** @test */
    public function by_motion_user_comment_vote_filter(){
        $this->markTestSkipped('user seems to work but then the motion query is showing all the users but not multiple ones for any single user');

        $this->response = $this->call("GET",'/api/user/'.static::$userCommentVoting->id."/comment_vote",['motion_id'=>static::$aNormalMotion->id]);

    }
  

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
