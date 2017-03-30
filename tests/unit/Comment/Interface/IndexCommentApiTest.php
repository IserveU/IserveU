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
                            'data'  => [
                                '*' => [
                                    'id',
                                    'text',
                                    'created_at',
                                    'commentRank',
                                    'motionTitle',
                                    'motionSlug',
                                    'commentWriter' => [
                                        'community' => [
                                            'adjective',
                                        ],
                                    ],
                                ],
                            ],
                        ]);

        $this->seeNumberOfResults(20); //Default pagination count
        $this->seeOrderInField('desc', 'commentRank'); //Default order
    }

    /** @test */
    public function comment_filter_by_comment_rank_ascending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['commentRank'=>'asc']])
                ->assertResponseStatus(200)
                ->seeOrderInField('asc', 'commentRank');
    }

    /** @test */
    public function comment_filter_by_comment_rank_descending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['commentRank'=>'desc']])
                ->assertResponseStatus(200)
                ->seeOrderInField('desc', 'commentRank');
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
