<?php

include_once 'MotionApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteMotionApiTest extends MotionApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test  ******************/
    public function delete_motion_correct_response()
    {
        $this->signInAsRole('administrator');

        $motion = factory(App\Motion::class)->create();

        $this->delete('/api/motion/'.$motion->id)
            ->assertResponseStatus(200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
