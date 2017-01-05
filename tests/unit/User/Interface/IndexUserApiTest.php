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
                        'community',
                    ],
                ],
            ]);
        $this->seeOrderInTimeField(); //Default order
        $this->dontSeeJson(['status' => 'private']);
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
                ->dontSeeJson(['status' => 'public']);
    }

    /** @test */
    public function user_filter_by_status_public()
    {
        $this->json('GET', $this->route, ['status' => 'public'])
                ->assertResponseStatus(200)
                ->dontSeeJson(['status' => 'private']);
    }

    /** @test */
    public function user_filter_by_identity_verified()
    {
        $this->json('GET', $this->route, ['identityVerified' => 1])
        ->assertResponseStatus(200)
        ->dontSeeJson(['identity_verified' => 0]);
    }

    /** @test */
    public function user_filter_by_identity_not_verified()
    {
        $this->json('GET', $this->route, ['identityVerified' => 0])
        ->assertResponseStatus(200)
        ->dontSeeJson(['identity_verified' => 1]);
    }

    /** @test */
    public function user_filter_by_address_verified()
    {
        $this->json('GET', $this->route, ['addressVerified' => 1])
        ->assertResponseStatus(200)
        ->dontSeeJson(['address_verified' => false]);
    }

    /** @test */
    public function user_filter_by_adrdress_not_verified()
    {
        $this->json('GET', $this->route, ['addressVerified' => 0])
        ->assertResponseStatus(200)
        ->dontSeeJson(['address_verified' => true]);
    }

    /** @test */
    public function user_filter_by_lastName()
    {
        $lastName = 'Krieger1337';

        factory(App\User::class)->create(
            ['last_name' => $lastName,
             'status'    => 'public']);

        $this->json('GET', $this->route, ['lastName' => $lastName])
                ->assertResponseStatus(200)
                ->seeJson(['total' => 1])
                ->seeJson(['last_name' => $lastName]);
    }

    /** @test */
    public function user_filter_by_firstName()
    {
        $firstName = 'Vinh1337';

        factory(App\User::class)->create(
            ['first_name' => $firstName,
             'status'    => 'public']);

        $this->json('GET', $this->route, ['firstName' => $firstName])
                ->assertResponseStatus(200)
                ->seeJson(['total' => 1])
                ->seeJson(['first_name' => $firstName]);
    }

    /** @test */
    public function user_filter_by_middleName()
    {
        $middleName = 'Murray1337';

        factory(App\User::class)->create(
            ['middle_name' => $middleName,
             'status'    => 'public']);

        $this->json('GET', $this->route, ['middleName' => $middleName])
                ->assertResponseStatus(200)
                ->seeJson(['total' => 1])
                ->seeJson(['middle_name' => $middleName]);
    }

    /** @test */
    public function user_filter_by_allNames()
    {
        $name = 'SeaHorse42069';

        factory(App\User::class)->create(
            ['first_name' => $name,
             'status'    => 'public']);

        factory(App\User::class)->create(
            ['middle_name' => $name,
             'status'    => 'public']);

        factory(App\User::class)->create(
            ['last_name' => $name,
             'status'    => 'public']);

        $this->json('GET', $this->route, ['allNames' => $name])
                ->assertResponseStatus(200)
                ->seeJson(['total' => 3])
                ->seeJson(['first_name' => $name,
                           'last_name'  => $name,
                           'last_name'  => $name]);
    }

    /** @test */
    public function user_filter_result_limit()
    {
        $this->json('GET', $this->route, ['limit' => 11])
        ->assertResponseStatus(200)
        ->SeeJson(['per_page' => 11]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
