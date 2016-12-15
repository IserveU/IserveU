<?php

include_once 'CommunityApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowCommunityApiTest extends CommunityApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test */
    public function show_community_test()
    {
        $this->signInAsRole('administrator');

        $community = factory(App\Community::class)->create();

        $this->visit('/api/community/'.$community->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'name', 'adjective', 'slug', 'active',
            ])
            ->dontSeeJson([
                'motions',
            ]);
    }
}
