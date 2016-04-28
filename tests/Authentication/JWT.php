<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JWT extends TestCase
{
    
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown(){
        parent::tearDown();
    }


	/** @test **/
    public function login_and_get_token()
    {	
        $faker = \Faker\Factory::create();

        $password = $faker->password;

        $user = factory(App\User::class)->create([
        	'password'	=> 	$password
        ]);

        $this->post( '/authenticate',['email' => $user->email,'password' => $password]);

        $content = json_decode($this->response->getContent());
        if(!$content){
            dd($this->response->getContent());
        }
        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');
    }

}
