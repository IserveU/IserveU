<?php

include_once 'PageApi.php';

use App\Page;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeletePageApiTest extends PageApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test  ******************/
    public function delete_page_correct_response()
    {
        $this->signInAsRole('administrator');

        $page = factory(App\Page::class)->create();

        $this->delete('/api/page/'.$page->slug)
            ->assertResponseStatus(200)
            ->dontSeeInDatabase('pages', ['id' => $page->id]);
    }

    /** @test  ******************/
    public function delete_home_page_correct_response()
    {
        $this->signInAsRole('administrator');

        $page = Page::first();

        $this->delete('/api/page/'.$page->slug)
            ->assertResponseStatus(403)
            ->seeInDatabase('pages', ['id' => 1]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
