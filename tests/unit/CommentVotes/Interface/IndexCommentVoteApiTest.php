<?php

include_once 'CommentVoteApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexCommentVoteApiTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function filter_commentvote_by()
    {
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
