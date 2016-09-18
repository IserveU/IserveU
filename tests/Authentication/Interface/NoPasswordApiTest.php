<?php
include_once('AuthenticateApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NoPasswordApiTest extends AuthenticateApi
{
    use DatabaseTransactions;    



    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES 

   


    /** @test **/
    public function submit_remember_token_and_reset_password()
    {   
        $user = factory(App\User::class)->create();
        
        $this->get('/authenticate/'.$user->remember_token)
            ->assertResponseStatus(200)
            ->see($user->email)
            ->see($user->api_token);
    }

   
    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
