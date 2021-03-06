<?php

include_once 'AuthenticateApi.php';

use App\OneTimeToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResetPasswordApiTest extends AuthenticateApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test **/
    public function submit_lost_password_request_for_email_that_exists()
    {
        $user = factory(App\User::class)->create();

        $this->post('authenticate/resetpassword', ['email' => $user->email])
            ->assertResponseStatus(200);
    }

    /** @test **/
    public function submit_one_time_token_and_reset_password()
    {
        $user = factory(App\User::class)->create();

        $token = OneTimeToken::generateFor($user);

        $this->get('/authenticate/'.$token->token)
            ->assertResponseStatus(200)
            ->see($user->email)
            ->see($user->api_token);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test **/
    public function submit_lost_password_request_for_email_that_does_not_exists()
    {
        $faker = \Faker\Factory::create();

        $this->doesntExpectEvents(SendPasswordReset::class);

        $this->post('authenticate/resetpassword', ['email' => $faker->email])
            ->assertResponseStatus(404);
    }
}
