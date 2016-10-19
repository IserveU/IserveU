<?php

include_once 'PageApi.php';

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

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
