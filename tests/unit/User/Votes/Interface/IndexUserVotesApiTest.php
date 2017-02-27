<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

include_once __DIR__.'/../UserVotesTests.php';

class IndexUserVotesApiTest extends UserVotesTests
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        //Creates an newer vote to check ordering
        factory(App\Vote::class)->create([
            'user_id'   => static::$votingUser->id,
        ]);

        $this->signIn(static::$votingUser);
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function default_user_vote_filter()
    {
        //Make sure a vote is up to date
        static::$votingUser->votes->first()->touch();

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
                    '*' => ['id', 'position', 'motion_id', 'deferred_to_id', '_motion_title'],
                   ],
                ])
                ->seeOrderInTimeField('desc', 'updated_at');
    }

    /** @test */
    public function user_vote_filter_by_updated_at_ascending()
    {
        $votes = factory(App\Vote::class, 1)->create([
            'user_id'   => static::$votingUser->id,
        ]);

        $this->json('GET', $this->route, ['orderBy' => ['updated_at'=>'asc']])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('asc', 'updated_at');
    }

    /** @test */
    public function user_vote_filter_by_updated_at_descending()
    {
        $votes = factory(App\Vote::class, 1)->create([
            'user_id'   => static::$votingUser->id,
        ]);

        $this->json('GET', $this->route, ['orderBy' => ['updated_at'=>'desc']])//->dump()
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('desc', 'updated_at');
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
