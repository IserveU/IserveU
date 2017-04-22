<?php

include_once 'CommunityApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteCommunityApiTest extends CommunityApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test  ******************/
    public function delete_community_correct_response()
    {
        $this->signInAsRole('administrator');

        $community = factory(App\Community::class)->create();

        $this->delete('/api/community/'.$community->slug)
            ->assertResponseStatus(200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
