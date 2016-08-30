<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionFilterPermissionTest extends TestCase
{
    use DatabaseTransactions;    


    /*****************************************************************
    *
    *                   Basic CRUD functions:
    *
    ******************************************************************/
    


    /** @test */
    public function motion_index_permissions_working()
    {
        $this->signInAsPermissionedUser('show-motion');
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
    public function motion_index_without_permission()
    {
        $this->signIn();
        $motions = generateMotions($this);
        //Default with no filters
        filterCheck(
                $this,
                [
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed']
                ],[
                    $motions['motionDraft'],
                    $motions['motionMyDraft'],
                    $motions['motionReview'],
                    $motions['motionMyReview']
                ]
        );
        $this->assertResponseStatus(200);

        //Filter to see published
        filterCheck(
                $this,
                [
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                ],[
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionMyDraft'],
                    $motions['motionReview'],
                    $motions['motionMyReview']
                ],
                ['status'=>[2]]
        );
        $this->assertResponseStatus(200);
   
       //Filter to see my drafts
        filterCheck(
                $this,
                [
                    $motions['motionMyDraft'],
                    $motions['motionMyReview']
                ],[
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionReview']
                ],
                ['status'=>[0,1]]
        );
        $this->assertResponseStatus(200);

      
        //Filter to see published
        filterCheck(
                $this,
                [
                    $motions['motionMyDraft'],
                ],[
                    $motions['motionMyReview'],
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionReview']
                ],
                ['status'=>[0]]
        );
        $this->assertResponseStatus(200);    
    }



}
