<?php

include_once 'PageApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdatePageApiTest extends PageApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->modelToUpdate = factory($this->class)->create();

        $this->signInAsRole('administrator');
    }

    /** @test  ******************/
    public function update_page_with_title()
    {
        $this->updateFieldsGetSee(['title'], 200);
    }

    /** @test  ******************/
    public function update_page_with_content()
    {
        $this->updateFieldsGetSee(['title', 'content'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function update_page_with_empty_title_fails()
    {
        $this->updateContentGetSee([
            'title' => '',
        ], 400);
    }
}
