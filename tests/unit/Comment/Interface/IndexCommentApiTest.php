<?php

include_once 'CommentApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexCommentApiTest extends CommentApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function comment_filter_defaults()
    {
        $this->getStaticMotion();

        $this->get($this->route)
             ->assertResponseStatus(200)
             ->seeJsonStructure([
                '*' => [
                    'id', 'text', 'created_at', 'commentRank', 'motionTitle', 'motionId',
                    'user' => [
                        'community' => [
                            'adjective',
                        ],
                    ],

                ],
            ]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
