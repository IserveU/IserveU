<?php

include_once 'AuthenticateApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticateApiTest extends AuthenticateApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test **/
    public function login_with_correct_details()
    {
        $faker = \Faker\Factory::create();

        $password = $faker->password;

        $user = factory(App\User::class)->create([
            'password'    => $password,
        ]);

        $this->post('/authenticate', ['email' => $user->email, 'password' => $password])
            ->assertResponseStatus(200)
            ->seeJson([
                'api_token' => $user->api_token,
            ]);
    }

    /** @test **/
    public function login_as_new_user_and_get_token_and_permissions()
    {
        $this->setSettings(['security.verify_citizens' => 0]);

        $user = factory(App\User::class)->create([
            'password'  => 'abcd1234',
        ]);

        $this->post('authenticate', array_merge($user->skipVisibility()->setVisible(['email'])->toArray(), ['password' => 'abcd1234']))
            ->assertResponseStatus(200)
            ->seeJson([
                'api_token'     => $user->api_token,
                'first_name'    => $user->first_name,
            ])->see('permissions');
    }

    /** @test **/
    public function login_as_minimal_specs_new_user()
    {
        $user = factory(App\User::class)->create([
            'password'  => 'abcd1234!',
        ]);

        $this->post('/authenticate', ['email' => $user->email, 'password' => 'abcd1234!'])
             ->assertResponseStatus(200)
             ->seeJson([
                'api_token' => $user->api_token,
             ]);
    }

    /** @test **/
    public function try_to_register_user_with_duplicate_email_address()
    {
        $user = factory(App\User::class)->create();

        $this->post('/api/user', $user->setVisible(['first_name', 'last_name', 'email', 'password'])->toArray());

        $this->assertResponseStatus(400);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test **/
    public function login_fails_with_incorrect_password()
    {
        $user = factory(App\User::class)->create();

        $this->post('/authenticate', ['email' => $user->email, 'password' => 'wrongpassword'])
             ->assertResponseStatus(403)
             ->seeJson([
                'error'     => 'Invalid credentials',
                'message'   => 'Either your username or password are incorrect',
            ]);
    }

    /** @test **/
    public function login_fails_with_non_existant_user()
    {
        $this->post('/authenticate', ['password' => 'abcd1234', 'email' => 'notarealpersonatallhere@iserveu.ca'])
             ->assertResponseStatus(401)
             ->seeJson([
                'error'     => 'Invalid credentials',
                'message'   => 'This user does not exist',
            ]);
    }
}
