<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexMotionVoteApiTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function default_motion_vote_filter()
    {
        $motion = $this->getStaticMotion();

        //This failed once to find the key "Abstain"
        $this->get('/api/motion/'.$motion->slug.'/vote')
                ->assertResponseStatus(200)
                ->seeJsonStructure([
                    'abstain' => [
                        'active',
                    ],
                    'agree' => [
                        'active',
                    ],
                    'disagree' => [
                        'active',
                    ],
                ]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
