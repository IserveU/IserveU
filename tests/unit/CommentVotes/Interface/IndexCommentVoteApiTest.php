<?php

include_once 'CommentVoteApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexCommentVoteApiTest extends CommentVoteApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function can_get_own_comment_votes()
    {
        $this->signInAsRole('administrator');


        $this->get($this->route)
            ->assertResponseStatus(200);


    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
