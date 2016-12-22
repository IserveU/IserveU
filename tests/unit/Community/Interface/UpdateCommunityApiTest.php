<?php

include_once 'CommunityApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateCommunityApiTest extends CommunityApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->modelToUpdate = factory($this->class)->create();

        $this->signInAsRole('administrator');
    }

    /** @test  ******************/
    public function update_community_with_new_name()
    {
        $this->updateFieldsGetSee(['name'], 200);
    }

    /** @test  ******************/
    public function update_community_with_new_adjective()
    {
        $this->updateFieldsGetSee(['adjective'], 200);
    }

    /** @test  ******************/
    public function update_community_with_active()
    {
        $this->updateFieldsGetSee(['active'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function update_community_with_empty_name_fails()
    {
        $this->updateContentGetSee([
            'name'     => '',
        ], 400);
    }

    /** @test  ******************/
    public function update_community_with_nonboolean_active_fails()
    {
        $this->updateContentGetSee([
            'active'     => 'Hyperdrive Maximum',
        ], 400);

        $this->updateContentGetSee([
            'active'     => 'true',
        ], 400);

        $this->updateContentGetSee([
            'active'     => 9001,
        ], 400);
    }

    /** @test  ******************/
    public function update_community_slug_fails()
    {
        $this->updateContentGetSee([
            'slug'     => 'overriderstrider',
        ], 400);
    }
}
