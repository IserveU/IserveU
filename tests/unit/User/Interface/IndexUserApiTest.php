<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexUserApiTest extends TestCase
{
    use DatabaseTransactions;

    private static $users;

    public function setUp()
    {
        parent::setUp();

        if (is_null(static::$users)) {
            static::$users = factory(App\User::class, 5)->create();
        }
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function default_user_filter()
    {
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
