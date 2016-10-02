<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentVotesPermissionsTest extends TestCase
{
  
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();

    }

    /*****************************************************************
    *
    *                   Basic CRUD functions:
    *
    ******************************************************************/
    

    /** @test */
    public function it_can_vote_on_another_persons_comment()
    {
        $this->signInAsAdmin();
        $motion = factory(App\Motion::class,'published')->create();

        $this->get('/api/motion/'.$motion->id)
            ->assertResponseStatus(200);


        $this->signInAsPermissionedUser('create-comment_vote');
 
        $comment = factory(App\Comment::class)->create(); //Someone's comment

        $vote = factory(App\Vote::class)->create([  //This users vote
            'motion_id' =>  $comment->vote->motion_id,
            'user_id'   =>  $this->user->id
        ]);
        

        // Make a comment vote
        $commentVote = factory(App\CommentVote::class)
            ->make(['comment_id' => $comment->id])
            ->setVisible(['position'])
            ->toArray();


        $this->post('/api/comment/'.$comment->id.'/comment_vote',$commentVote)
            ->assertResponseStatus(200);

        $this->seeInDatabase('comment_votes', 
            [
                'vote_id' => $vote->id, 
                'position' => $commentVote['position']
            ]
        );
    }


    /** @test */
    public function it_can_update_own_comment_vote()
    {
        $this->signInAsPermissionedUser('create-comment_vote');

        // Make a comment vote that is mine
        $commentVote = factory(App\CommentVote::class)->create();        
        $commentVote->vote->user_id = $this->user->id;
        $commentVote->vote->save();

        $votePosition = switchVotePosition();

        $this->call('PATCH', '/api/comment_vote/'.$commentVote->id, 
            [
                'position' => $votePosition
            ]
        );
        $this->assertResponseOk();
        $this->seeInDatabase('comment_votes', ['id' => $commentVote->id, 'position' => $votePosition]);
    }


    /** @test */
    public function it_can_delete_own_comment_vote()
    {
        $this->signInAsPermissionedUser('create-comment_vote');

        $commentVote = factory(App\CommentVote::class)->create();        
        $commentVote->vote->user_id = $this->user->id;
        $commentVote->vote->save();
        
        $this->delete('/api/comment_vote/'.$commentVote->id);
        $this->assertResponseOk();
        $this->notSeeInDatabase('comment_votes', ['id' => $commentVote->id]);
    }




}
