<?php

include_once 'UserApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexUserApiTest extends UserApi
{
    use DatabaseTransactions;

    private static $users;

    public function setUp()
    {
        parent::setUp();
        $this->signInAsAdmin();

        factory(App\User::class, 5)->create();

    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function user_filter_defaults()
    {
        $this->get($this->route)
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
                        'status',
                        'first_name',
                        'last_name',
                        'community_id',
                        'community'
                    ],
                ],
            ]);
        $this->seeOrderInTimeField(); //Default order
        $this->dontSee('private');
    }

    /** @test */
    public function user_filter_by_created_at_ascending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['created_at'=>'asc']])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('asc', 'created_at');
    }

    /** @test */
    public function user_filter_by_created_at_descending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['created_at'=>'desc']])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('desc', 'created_at');
    }

    /** @test */
    public function user_filter_by_id_descending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['id'=>'desc']])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('desc', 'id');
    }

    /** @test */
    public function user_filter_by_id_ascending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['id'=>'asc']])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('asc', 'id');
    }

    /** @test */
    public function user_filter_by_status_private()
    {
        $this->json('GET', $this->route, ['status' => 'private'])
                ->assertResponseStatus(200)
                ->dontSee('public');
    }

    /** @test */
    public function user_filter_by_status_public()
    {
        $this->json('GET', $this->route, ['status' => 'public'])
                ->assertResponseStatus(200)
                ->dontSee('private');
    }

    /** @test */
    public function user_filter_by_identity_verified()
    {
        $this->json('GET', $this->route, ['identity_verified' => 1])
        ->assertResponseStatus(200)
        ->dontSeeJson(['identity_verified' => 0]);
    }

    /** @test */
    public function user_filter_by_identity_not_verified()
    {
        $this->json('GET', $this->route, ['identity_verified' => 0])
        ->assertResponseStatus(200)
        ->dontSeeJson(['identity_verified' => 1]);
    }
    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
