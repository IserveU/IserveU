<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DefaultUserLoginTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    /** @test **/
    public function login_as_default_user_and_get_token()
    {
        $this->post('authenticate', ['password' => 'abcd1234', 'email' => 'admin@iserveu.ca'])
            ->assertResponseStatus(200)
            ->seeJson([
                'api_token' => User::where('email', 'admin@iserveu.ca')->first()->api_token,
            ]);
    }

    /** @test **/
    public function new_users_have_no_permissions()
    {
        $user = factory(App\User::class)->make()->skipVisibility()->setVisible(['first_name', 'last_name', 'email'])->toArray();

        $user['password'] = 'abcd1234';

        $this->post('/api/user', $user)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'permissions' => [],
            ]);
    }
}
