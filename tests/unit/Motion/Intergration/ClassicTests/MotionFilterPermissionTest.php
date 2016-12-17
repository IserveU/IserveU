<?php

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
        // as defaults we only allow published and closed
        // on MotionFilter class.
        filterCheck(
                $this,
                [
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    // $motions['motionDraft'],
                    // $motions['motionMyDraft'],
                    // $motions['motionReview'],
                    // $motions['motionMyReview'],
                ], []
        );
        $this->assertResponseStatus(200);

        //Filter to see drafts
        filterCheck(
                $this,
                [
                    $motions['motionDraft'],
                    $motions['motionMyDraft'],
                ], [
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionReview'],
                    $motions['motionMyReview'],
                ],
                ['status' => ['draft']]
        );
        $this->assertResponseStatus(200);

        //Filter to see all reviews
        filterCheck(
                $this,
                [
                    $motions['motionMyReview'],
                    $motions['motionReview'],
                ], [
                    $motions['motionMyDraft'],
                    $motions['motionDraft'],
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                ],
                ['status' => ['review']]
        );
        $this->assertResponseStatus(200);

        //Filter to see published
        filterCheck(
                $this,
                [
                    $motions['motionMyDraft'],
                    $motions['motionMyReview'],
                    $motions['motionDraft'],
                    $motions['motionReview'],
                ], [
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                ],
                ['status' => ['draft', 'review']]
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
                    $motions['motionMyClosed'],
                ], [
                    $motions['motionDraft'],
                    $motions['motionMyDraft'],
                    $motions['motionReview'],
                    $motions['motionMyReview'],
                ]
        );
        $this->assertResponseStatus(200);

        //Filter to see published
        filterCheck(
                $this,
                [
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                ], [
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionMyDraft'],
                    $motions['motionReview'],
                    $motions['motionMyReview'],
                ],
                ['status' => ['published']]
        );
        $this->assertResponseStatus(200);

       //Filter to see my drafts
        filterCheck(
                $this,
                [
                    $motions['motionMyDraft'],
                    $motions['motionMyReview'],
                ], [
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionReview'],
                ],
                ['status' => ['draft', 'review']]
        );
        $this->assertResponseStatus(200);

        //Filter to see published
        filterCheck(
                $this,
                [
                    $motions['motionMyDraft'],
                ], [
                    $motions['motionMyReview'],
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionReview'],
                ],
                ['status' => ['draft']]
        );
        $this->assertResponseStatus(200);
    }
}
