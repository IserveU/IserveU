<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexVoteApiTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function filter_vote_by()
    {
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
