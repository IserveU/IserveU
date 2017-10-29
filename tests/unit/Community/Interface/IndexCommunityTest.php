<?php

include_once 'CommunityApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexCommunityApiTest extends CommunityApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function default_community_filter()
    {
        $this->get($this->route)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'total',
                'per_page',
                'current_page',
                'last_page',
                'next_page_url',
                'prev_page_url',
                'from',
                'to',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'adjective',
                        'active',
                    ],
                ],
            ]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
