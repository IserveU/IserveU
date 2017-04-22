<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexUserTransformerTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test  ******************/
    public function show_other_public_user_correct_fields()
    {
    }

    /** @test  ******************/
    public function show_other_private_user_correct_fields()
    {
    }

    /** @test  ******************/
    public function show_own_private_user_correct_fields()
    {
    }

    /** @test  ******************/
    public function show_own_public_user_correct_fields()
    {
    }

    /** @test  ******************/
    public function show_nopermission_correct_fields()
    {
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
