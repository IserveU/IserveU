<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateAndLoginNewUserTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    /** @test **/
    public function register_self_as_new_user_and_get_details()
    {
        $user = factory(App\User::class)->make()->skipVisibility()->setVisible(['first_name', 'last_name', 'email'])->toArray();

        $user['password'] = 'abcd1234';

        $this->post('/api/user', $user)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'permissions',
                'remember_token',
            ]);

        $this->seeInDatabase('users', ['email' => $user['email'], 'first_name' => $user['first_name']]);
    }

    /** @test **/
    public function check_instant_citizen_verify_off()
    {
        $user = factory(App\User::class)->make()->skipVisibility()->setVisible(['first_name', 'last_name', 'email'])->toArray();

        $user['password'] = 'abcd1234';

        $this->post('/api/user', $user)
             ->assertResponseStatus(200)
             ->dontSee('create-vote')
             ->seeInDatabase('users', ['first_name' => $user['first_name']]);

        $apiToken = json_decode($this->response->getContent())->api_token;

        $user = getUserWithToken($apiToken);

        $citizenRole = \App\Role::where('name', 'citizen')->first();

        $this->dontSeeInDatabase('role_user', ['user_id' => $user->id, 'role_id' => $citizenRole->id]);
    }
}
