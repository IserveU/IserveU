<?php

include_once 'CommunityApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreCommunityApiTest extends CommunityApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->signInAsRole('administrator');
    }

    /** @test  ******************/
    public function store_community_with_name()
    {
        $this->storeFieldsGetSee(['name'], 200);
    }

    /** @test  ******************/
    public function store_community_with_adjective()
    {
        $this->storeFieldsGetSee(['name', 'adjective'], 200);
    }

    /** @test  ******************/
    public function store_community_with_active()
    {
        $this->storeFieldsGetSee(['name', 'active'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function store_community_with_no_name_fails()
    {
        $this->storeContentGetSee([
            'name'     => '',
        ], 400);
    }

    /** @test  ******************/
    public function store_community_name_with_an_array_fails()
    {
        $this->storeContentGetSee([
            'name'     => ['titles'],
        ], 400);
    }

    /** @test  ******************/
    public function store_community_active_as_nonboolean_fails()
    {
        $this->storeContentGetSee([
            'name'      => 'Name',
            'active'    => 'yes',
        ], 400);

        $this->storeContentGetSee([
            'name'      => 'Name',
            'active'    => 'true',
        ], 400);
    }

    /** @test  ******************/
    public function store_community_slug_fails()
    {
        $this->storeContentGetSee([
            'name'     => 'Montreal',
            'slug'     => 'montreyall',
        ], 400);
    }
}
