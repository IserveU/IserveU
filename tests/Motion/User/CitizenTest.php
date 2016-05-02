<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CitizenTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
        $this->user->addUserRoleByName('citizen');
    }

    /** @test */
    public function it_can_see_motion_index()
    {
        $motionDraft = factory(App\Motion::class,'draft')->create();
        $motionMyDraft = factory(App\Motion::class,'draft')->create([
            'user_id'   => $this->user->id 
        ]);
        $motionReview = factory(App\Motion::class,'review')->create();
        $motionMyReview = factory(App\Motion::class,'review')->create([
            'user_id'   => $this->user->id 
        ]);
        $motionPublished = factory(App\Motion::class,'published')->create();
        $motionMyPublished = factory(App\Motion::class,'published')->create([
            'user_id'   => $this->user->id
        ]);
        $motionClosed = factory(App\Motion::class,'closed')->create();
        $motionMyClosed = factory(App\Motion::class,'closed')->create([
            'user_id'   => $this->user->id
        ]);

        $this->call('GET', '/api/motion/');
        $this->assertResponseStatus(403);

        $this->call('GET', '/api/motion/',['status'=>0]);
        $this->assertResponseStatus(403);

        $this->call('GET', '/api/motion/',['status'=>1]);
        $this->assertResponseStatus(403);

        $this->call('GET', '/api/motion/',['status'=>2]);
        $this->assertResponseStatus(200);

        $this->call('GET', '/api/motion/',['status'=>3]);
        $this->assertResponseStatus(200);


        $this->call('GET', '/api/motion/',['limit'=>50,'status'=>0,'user_id'=>$this->user->id]);
        $this->dontSee($motionDraft->title);
        $this->see($motionMyDraft->title);
        $this->dontSee($motionReview->title);
        $this->dontSee($motionMyReview->title);
        $this->dontSee($motionPublished->title);
        $this->dontSee($motionMyPublished->title);
        $this->dontSee($motionClosed->title);
        $this->dontSee($motionMyClosed->title);
      
        $this->call('GET', '/api/motion/',['limit'=>50,'status'=>1,'user_id'=>$this->user->id]);
        $this->dontSee($motionDraft->title);
        $this->dontSee($motionMyDraft->title);
        $this->dontSee($motionReview->title);
        $this->see($motionMyReview->title);
        $this->dontSee($motionPublished->title);
        $this->dontSee($motionMyPublished->title);
        $this->dontSee($motionClosed->title);
        $this->dontSee($motionMyClosed->title);

        $this->call('GET', '/api/motion/',['limit'=>50,'status'=>2,'user_id'=>$this->user->id]);
        $this->dontSee($motionDraft->title);
        $this->dontSee($motionMyDraft->title);
        $this->dontSee($motionReview->title);
        $this->dontSee($motionMyReview->title);
        $this->dontSee($motionPublished->title);
        $this->see($motionMyPublished->title);
        $this->dontSee($motionClosed->title);
        $this->dontSee($motionMyClosed->title);

            //If not filtering user
            $this->call('GET', '/api/motion/',['limit'=>5000,'status'=>2]);
            $this->dontSee($motionDraft->title);
            $this->dontSee($motionMyDraft->title);
            $this->dontSee($motionReview->title);
            $this->dontSee($motionMyReview->title);
            $this->see($motionPublished->title);
            $this->see($motionMyPublished->title);
            $this->dontSee($motionClosed->title);
            $this->dontSee($motionMyClosed->title);


        $this->call('GET', '/api/motion/',['limit'=>50,'status'=>3,'user_id'=>$this->user->id]);
        $this->dontSee($motionDraft->title);
        $this->dontSee($motionMyDraft->title);
        $this->dontSee($motionReview->title);
        $this->dontSee($motionMyReview->title);
        $this->dontSee($motionPublished->title);
        $this->dontSee($motionMyPublished->title);
        $this->dontSee($motionClosed->title);
        $this->see($motionMyClosed->title);

        // If not filtering user
        $this->call('GET', '/api/motion/',['limit'=>5000,'status'=>3]);
            $this->dontSee($motionDraft->title);
            $this->dontSee($motionMyDraft->title);
            $this->dontSee($motionReview->title);
            $this->dontSee($motionMyReview->title);
            $this->dontSee($motionPublished->title);
            $this->dontSee($motionMyPublished->title);
            $this->see($motionClosed->title);
            $this->see($motionMyClosed->title);

    }

    /** @test */
    public function it_can_see_a_motion()
    {
        $motion = factory(App\Motion::class,'published')->create();

        $this->call('GET', '/api/motion/'.$motion->id);
        $this->assertResponseOk();
        $this->seeJson([ 'id' => $motion->id, 'text' => $motion->text ]);
    }

    /** @test */
    public function it_cannot_create_a_draft_motion_for_another_user()
    {
    
        $motion = factory(App\Motion::class,'draft')->make()->toArray();

        $this->post('/api/motion/',$motion);

        $this->assertResponseStatus(403);

        $this->dontSeeInDatabase('motions',array('title'=>$motion['title']));

    }

    /** @test */
    public function it_can_create_a_draft_motion()
    {
        $motion = factory(App\Motion::class,'draft')->make([
            'user_id'   =>  $this->user->id
        ])->toArray();

        $this->post('/api/motion/',$motion);

        $this->assertResponseStatus(200);

        $this->seeInDatabase('motions',array('title'=>$motion['title']));
    }


    /** @test */
    public function it_can_see_own_draft_motion()
    {
        $motion = factory(App\Motion::class,'draft')->create([
            'user_id'   =>  $this->user->id
        ]);

        $this->get('/api/motion/'.$motion->id);

        $this->assertResponseStatus(200);

    }

    /** @test */
    public function it_cannot_create_a_published_motion()
    {
    
        $motion = factory(App\Motion::class,'published')->make()->toArray();

        $this->post('/api/motion/',$motion);

        $this->assertResponseStatus(403);        
    }

    /** @test */
    public function it_cannot_publish_a_draft_motion()
    {
    
        $motion = factory(App\Motion::class,'draft')->create([
            'user_id'   => $this->user->id
        ])->toArray();

        $motion['status'] = 2;

        $this->patch('/api/motion/'.$motion['id'],$motion);

        $this->assertResponseStatus(403);        
    }

    /** @test */
    public function it_can_create_a_vote()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        
        $response = $this->call('POST', '/api/vote', $vote);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['motion_id' => $motion->id, 'position' => $vote['position'], 'user_id' => $this->user->id]);
    }

    /** @test */ 
    public function it_can_create_a_comment()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote', $vote);
      

        $vote = $vote->getOriginalContent();
        
        $this->assertResponseOk();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);

        $this->call('POST', '/api/comment', $comment);
        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'vote_id' => $vote->id, 'text' => $comment['text'] ]);
    }

    /** @test */
    public function it_can_create_a_comment_vote()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote', $vote);
        $vote = $vote->getOriginalContent();
        
        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment', $comment);
        $comment = $comment->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'vote_id' => $vote->id, 'text' => $comment->text ]);

        // Make a comment vote
        $comment_vote = factory(App\CommentVote::class)->make(['comment_id' => $comment->id, 'vote_id' => $vote->id])->toArray();
        
        $this->call('POST', '/api/comment_vote', $comment_vote);
        $this->assertResponseOk();
        $this->seeInDatabase('comment_votes', [ 'comment_id' => $comment->id, 'vote_id' => $vote->id, 'position' => $comment_vote['position']  ]);

    }

    /** @test */
    public function it_can_update_vote()
    {
        $faker  = Faker\Factory::create();
        $motion = factory(App\Motion::class, 'published')->create();

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote', $vote);
        $vote = $vote->getOriginalContent();

        // Switch vote
        $new_position = $faker->shuffle(array(-1, 0, 1));
        $new_position = $new_position[$faker->numberBetween($min = 0, $max = 2)];

        // Update Vote
        $this->call('PATCH', '/api/vote/'.$vote->id, 
                  [ 'position' => $new_position, 'id' => $vote->id ]);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['motion_id' => $motion->id, 'position' => $new_position, 'user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_update_comment()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote', $vote);
        $vote = $vote->getOriginalContent();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment', $comment);
        $comment = $comment->getOriginalContent();

        // Update comment
        $new_comment = factory(App\Comment::class)->make(['id' => $comment->id])->toArray();

        $this->call('PATCH', '/api/comment/'.$comment->id, $new_comment);
        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'id' => $comment->id, 'text' => $new_comment['text'] ]);
    }

    /** @test */
    public function it_can_update_comment_vote()
    {
        $faker  = Faker\Factory::create();
        $motion = factory(App\Motion::class, 'published')->create();

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote', $vote);
        $vote = $vote->getOriginalContent();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment', $comment);
        $comment = $comment->getOriginalContent();

        // Make a comment vote
        $comment_vote = factory(App\CommentVote::class)->make(['comment_id' => $comment->id, 'vote_id' => $vote->id])->toArray();
        $comment_vote = $this->call('POST', '/api/comment_vote', $comment_vote);
        $comment_vote = $comment_vote->getOriginalContent();

        // Update comment vote
        $new_position = $faker->shuffle(array(-1, 0, 1));
        $new_position = $new_position[$faker->numberBetween($min = 0, $max = 2)];

        $this->call('PATCH', '/api/comment_vote/'.$comment_vote->id, 
                  [ 'position' => $new_position, 'id' => $vote->id ]);
        $this->assertResponseOk();
        $this->seeInDatabase('comment_votes', ['position' => $new_position, 'comment_id' => $comment->id]);

    }

    /** @test */
    public function it_can_delete_vote()
    {
        // As per the API delete route, you cannot delete a vote, you may only switch to abstain.
        $motion = factory(App\Motion::class, 'published')->create();

        // Create Vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote', $vote);
        $vote = $vote->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['motion_id' => $motion->id, 'position' => $vote->position, 'user_id' => $this->user->id]);

        // Delete Vote
        $this->call('DELETE', '/api/vote/'.$vote->id);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['motion_id' => $motion->id, 'position' => 0, 'user_id' => $this->user->id]);

    }

        /** @test */
    public function it_can_delete_comment()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote', $vote);
        $vote = $vote->getOriginalContent();
        
        $this->assertResponseOk();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment', $comment);
        $comment = $comment->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'vote_id' => $vote->id, 'text' => $comment['text'] ]);

        // Delete comment
        $delete = $this->call('DELETE', '/api/comment/'.$comment->id);
        $delete = $delete->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', ['deleted_at' => $delete->deleted_at]);
    }

        /** @test */
    public function it_can_delete_comment_vote()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote', $vote);
        $vote = $vote->getOriginalContent();
        
        $this->assertResponseOk();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment', $comment);
        $comment = $comment->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'vote_id' => $vote->id, 'text' => $comment['text'] ]);


        // Make a comment vote
        $comment_vote = factory(App\CommentVote::class)->make(['comment_id' => $comment->id, 'vote_id' => $vote->id])->toArray();
        $comment_vote = $this->call('POST', '/api/comment_vote', $comment_vote);
        $comment_vote = $comment_vote->getOriginalContent();

        $this->call('DELETE', '/api/comment_vote/'.$comment_vote->id);
        $this->assertResponseOk();
        $this->notSeeInDatabase('comment_votes', ['id' => $comment_vote->id]);
    }


    /*****************************************************************
    *
    *                          For Ike:
    *  - Fulfill the conditions of the function names and anything
    *    else that comes up.
    *
    ******************************************************************/


    // /** @test */
    // public function it_cannot_create_or_update_a_comment_without_having_voted()
    // {

    // }

    // /** @test */
    // public function it_cannot_create_or_update_a_comment_vote_without_having_voted()
    // {

    // }

    // /** @test */
    // public function it_cannot_create_a_motion()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_a_motion()
    // {

    // }

    // /** @test */
    // public function it_cannot_delete_a_motion()
    // {

    // }
    // /** @test */
    // public function it_cannot_see_an_unpublished_motion()
    // {

    // }

    // /** @test */
    // public function it_cannot_see_a_private_users_details()
    // {

    // }

    // * @test 
    // public function it_can_see_a_public_users_details()
    // {

    // }

    // /** @test */
    // public function it_can_see_its_own_details()
    // {

    // }

    // /** @test */
    // public function it_can_update_its_own_details()
    // {

    // }

    // /** @test */
    // public function it_can_see_its_updated_details()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_another_users_details()
    // {

    // }

    // /** @test */
    // public function it_cannot_assign_permissions_or_roles()
    // {

    // }

    // /** @test */
    // public function it_cannot_upload_a_background_image()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_the_deparments()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_the_ethnic_origins()
    // {

    // }

    // /** @test */
    // public function it_cannot_create_a_motion_file()
    // {

    // }

}
