<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetRememberTokenTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test **/
    public function user_created_has_remembertoken_set()
    {
        $user = factory(App\User::class)->create();

        $token = DB::table('users')->where('id', $user->id)->value('remember_token');

        $this->assertNotEquals(null, $token);
        $this->assertEquals(99, strlen($token));
    }
}