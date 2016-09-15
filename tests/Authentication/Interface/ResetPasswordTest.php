<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

use App\Events\SendPasswordReset;

class ResetPasswordTest extends TestCase
{
	use DatabaseTransactions;


    /** @test **/
    public function submit_lost_password_request_for_email_that_exists()
    {
        $user = factory(App\User::class)->create();
       
        $this->post('authenticate/resetpassword',['email'=>$user->email])
            ->assertResponseStatus(200);

    }


    /** @test **/
    public function submit_lost_password_request_for_email_that_does_not_exists()
    {   
        $faker = \Faker\Factory::create();

        $this->doesntExpectEvents(SendPasswordReset::class);

        $this->post('authenticate/resetpassword',['email'=>$faker->email])
            ->assertResponseStatus(403);

    }



    /** @test **/
    public function submit_remember_token_and_reset_password()
    {   
        $user = factory(App\User::class)->create();
        
        $this->get('/authenticate/'.$user->remember_token)
            ->assertResponseStatus(200)
            ->see($user->email)
            ->see($user->api_token);
    }



}
