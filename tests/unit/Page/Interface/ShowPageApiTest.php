<?php

include_once 'PageApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowPageApiTest extends PageApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test */
    public function show_page_by_slug()
    {
        $this->signInAsRole('administrator');

        $page = factory(App\Page::class)->create();


        $this->visit('/api/page/'.$page->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'id',
                'title',
                'slug',
                'text',
                'created_at',
                'updated_at',
            ])
            ->dontSeeInResponse([
                'content',
            ]);
    }

    /** @test */
    public function show_page_by_id()
    {
        $this->signInAsRole('administrator');

        $page = factory(App\Page::class)->create();


        $this->get('/api/page/'.$page->id)
            ->assertResponseStatus(200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
