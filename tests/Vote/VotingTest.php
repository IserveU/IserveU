<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VotingTest extends TestCase 
{

    /*****************************************************************
    *
    *   Tests to run complex voting trees. For example: 
    *	- checking that overall votes represents the amount in the database.
    *	- motions that cannot be voted on cannot (closed by status or by time)
    *	- users that cannot vote cannot; under complex operations:
    *		- user is verified, votes and then is unverified and cannot vote
    *		- votes are removed once unverified
    *		- make sure user is notified (email system)
    *	- deferred votes get cast at the right time with the right percentage
    *	- deferred votes are represented correctly
    *
    ******************************************************************/

    use DatabaseTransactions;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function cast_votes_and_result_equals_true()
    {
        // Create 50 Users
        $verifiedUsers = factory(App\User::class, 'verified', 2)->create()->each(function($u){
            $u->addUserRoleByName('citizen');
        });

        // Make 40 of them are regular citizens
        // $unverifiedUserArray = factory(App\User::class, 'unverified', 10)->create();

        // Create a new motion as a regular user
        $creator = $verifiedUsers->random();

        $this->signIn($creator);
        $motion  = postMotion($this);

        // Make 3 representatives (C1, C2, C3)
        $representatives = factory(App\User::class, 'verified', 3)->create()->each(function($u){
            $u->addUserRoleByName('representative');
        });

        $C1 = $representatives->pop();
        $C2 = $representatives->pop();
        $C3 = $representatives->pop();

        // Set motion to active with one of the representative accounts
        $C1 = $this->signIn($C1);
        $publishedMotion = publishMotion($motion, $C1);
       // dd($publishedMotion);
        $this->assertEquals($publishedMotion['status'], 2);

        // 1 representative votes for, one votes against
        $response = $this->post('/api/vote/', ['motion_id' => $publishedMotion['id'], 'position' => 1]);
      //  dd($response);
        $this->post('/api/vote/', ['motion_id' => $publishedMotion['id'], 'position' => -1]);

        // Check that 20 voted for, 20 voted against
        $motionVotes = $this->get('/api/motion/'.$publishedMotion['id'].'/vote');
//        dd( $motionVotes );

        // Check that the for/against add up to 42
        // Make one of the users that was not a citizen a citizen
        // Get that person to vote for
        // Check that the 21 show up as voting for
        // Make the remaining 6 users that were not a citizen a citizen
        // Check that the 21 show up as voting for and 20 against
        // Make C3 vote for the motion
        // Check that 26/27 are voting for and 13/14 are voting against
    }


}