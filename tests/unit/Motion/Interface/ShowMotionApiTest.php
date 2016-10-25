<?php

include_once 'MotionApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowMotionApiTest extends MotionApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test */
    public function show_motion_test()
    {
        $this->signInAsRole('administrator');

        $motion = factory(App\Motion::class)->create();


        $this->visit('/api/motion/'.$motion->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'title', 'text', 'summary', 'id', 'motionOpenForVoting', 'closing_at', 'userVote', 'status', 'updated_at', 'implementation',
                'department' => [
                    'name', 'id', 'slug',
                ],
            ])
            ->dontSeeJson([
                'votes', 'users',
            ]);
    }
}
