<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexUserVotesApiTest extends TestCase
{
    use DatabaseTransactions;

    protected static $votingUser;

    public function setUp()
    {
        parent::setUp();

        if (is_null(static::$votingUser)) {
            static::$votingUser = factory(App\User::class)->create();

            $votes = factory(App\Vote::class, 10)->create([
                'user_id'   => static::$votingUser->id,
            ]);
        }

        $this->signIn(static::$votingUser);
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function default_user_vote_filter()
    {
        $this->get('/api/user/'.static::$votingUser->id.'/vote')
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
                    'data'  => [
                    '*' => ['id', 'position', 'motion_id', 'deferred_to_id'],
                   ],
                ])
                ->seeNumberOfResults(10);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
