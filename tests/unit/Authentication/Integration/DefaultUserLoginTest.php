<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DefaultUserLoginTest extends TestCase
{
    use DatabaseTransactions;

    /** @test **/
    public function login_as_default_user_and_get_token()
    {
        $this->post('authenticate', ['password' => 'abcd1234', 'email' => 'admin@iserveu.ca'])
            ->assertResponseStatus(200)
            ->seeJson([
                'api_token' => User::first()->api_token,
            ]);
    }
}
