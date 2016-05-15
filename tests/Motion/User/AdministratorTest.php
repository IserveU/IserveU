<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdministratorTest extends TestCase
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
    public function motion_index_permissions_working()
    {
        $motions = generateMotions($this);
        //Default with no filters
        filterCheck(
                $this,
                [
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionMyDraft'],
                    $motions['motionReview'],
                    $motions['motionMyReview']
                ],[]
        );
        $this->assertResponseStatus(200);

        //Filter to see drafts
        filterCheck(
                $this,
                [
                    $motions['motionDraft'],
                    $motions['motionMyDraft']
                ],[
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionReview'],
                    $motions['motionMyReview']
                ],
                ['status'=>[0]]
        );
        $this->assertResponseStatus(200);
   
        //Filter to see all reviews
        filterCheck(
                $this,
                [
                    $motions['motionMyReview'],
                    $motions['motionReview']
                ],[
                    $motions['motionMyDraft'],
                    $motions['motionDraft'],
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed']
                ],
                ['status'=>[1]]
        );
        $this->assertResponseStatus(200);

        //Filter to see published
        filterCheck(
                $this,
                [
                    $motions['motionMyDraft'],
                    $motions['motionMyReview'],
                    $motions['motionDraft'],
                    $motions['motionReview']
                ],[
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed']
                ],
                ['status'=>[0,1]]
        );
        $this->assertResponseStatus(200);
    }

    /** @test */
    public function it_can_see_a_motion()
    {
        $motion =  factory(App\Motion::class, 'published')->create();

        $this->call('GET', '/api/motion/'.$motion->id);

        $this->assertResponseOk();
        $this->seeJson([ 'id' => $motion->id, 'text' => $motion->text ]);
    }

    /** @test */
    public function it_can_create_a_motion()
    {
        $motion  = postMotion($this);

        $this->seeInDatabase('motions', ['title' => $motion->title, 'summary' => $motion->summary]);

        $this->call('GET', '/api/motion/'.$motion->id);
        
        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_create_a_vote()
    {
        $vote = postVote($this);
     
        $this->seeInDatabase('votes', ['id' => $vote->id, 'position' => $vote->position, 'user_id' => $this->user->id]);
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
    public function it_can_create_a_comment_vote()
    {
        $comment_vote = postCommentVote($this);

        $this->seeInDatabase('comment_votes', [ 'id' => $comment_vote->id, 'position' => $comment_vote->position ]);
    }

    /** @test */
    public function it_can_update_a_closing_date_motion()
    {       
        $updated = factory(App\Motion::class)->create()->toArray();

        // Create new Closing Date
        $updated = array_merge($updated, createClosingDate() );

        $this->call('PATCH', '/api/motion/'.$updated['id'], $updated);

        $this->assertResponseOk();

        $this->seeInDatabase('motions', ['title' => $updated['title'], 'summary' => $updated['summary'], 'closing' => $updated['closing']]);
    }



    /** @test */
    public function it_can_update_vote()
    {
        $vote = postVote($this);

        $new_position = switchVotePosition();

        // Update Vote
        $this->call('PATCH', '/api/vote/'.$vote->id, 
                  [ 'position' => $new_position, 'id' => $vote->id ]);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['id' => $vote->id, 'position' => $new_position, 'user_id' => $this->user->id]);
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
    public function it_can_update_comment_vote()
    {
        $comment_vote = postCommentVote($this);

        $new_position = switchVotePosition();

        $this->call('PATCH', '/api/comment_vote/'.$comment_vote->id, 
                  [ 'position' => $new_position ]);
        $this->assertResponseOk();
        $this->seeInDatabase('comment_votes', ['id' => $comment_vote->id, 'position' => $new_position]);
    }

    /** @test */
    public function it_can_delete_a_motion()
    {
        $motion  = factory(App\Motion::class,'published')->create();
        
        // Delete Motion
        $response = $this->call('DELETE', '/api/motion/'.$motion->id);
        $this->assertResponseOk();
 
        $this->notSeeInDatabase('motions', ['id'=>$motion->id, 'deleted_at' => null ]);
    }

    /** @test */ 
    public function it_can_restore_a_motion()
    {
        $motion  = factory(App\Motion::class,'published')->create();
        
        // Delete Motion
        $this->call('DELETE', '/api/motion/'.$motion->id);
        $this->assertResponseOk();
        $this->notSeeInDatabase('motions', ['id'=>$motion->id, 'deleted_at' => null ]);

        // Restore motion
        $this->call('GET', '/api/motion/'.$motion->id.'/restore');
        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['id'=>$motion->id, 'deleted_at' => null]);
    }

    /** @test */
    public function it_can_delete_vote()
    {
        // As per the API delete route, you cannot delete a vote, you may only switch to abstain.
        
        $vote = postVote($this);
        
        // Delete Vote
        $this->call('DELETE', '/api/vote/'.$vote->id);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['id' => $vote->id, 'position' => 0, 'user_id' => $this->user->id]);

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

        /** @ test */
    public function it_can_delete_comment_vote()
    {
        $comment_vote = postCommentVote($this);
        
        $this->call('DELETE', '/api/comment_vote/'.$comment_vote->id);
        $this->assertResponseOk();
        $this->notSeeInDatabase('comment_votes', ['id' => $comment_vote->id]);
    }



        /** @test */
    public function update_a_voted_on_motion_title()
    {       
        $faker = \Faker\Factory::create();
        $motion = $vote = factory(App\Vote::class)->create()->motion;

        $newTitle = $faker->word;

        $this->patch('/api/motion/'.$motion->id,['title'=>$newTitle]);

        $this->assertResponseStatus(200);

    }

    /*****************************************************************
    *
    *                          For Ike:
    *  - replicate the CRUD functions for user details
    *  - be able to assign roles and take away roles from other users
    *  - replicate the CRUD functions for other user's votes/comments (these should fail)
    *  - be able to switch the status of a motion from 'draft' to 'published', etc.
    *
    *
    *  Note: if you complete representative tests first, and then copy it over, it should replicate exactly
    *        what it is you need; and you would just flip the negative tests from 403/401's to 200's. Saving you time.
    *
    ******************************************************************/


}
