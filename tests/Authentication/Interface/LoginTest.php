<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class LoginTest extends TestCase
{
	use DatabaseTransactions;


    /** @test **/
    public function login_as_new_user_and_get_token()
    {   

        $user = factory(App\User::class)->create([
            'password'  => 'abcd1234'
        ]);


        $this->post('authenticate',array_merge($user->setVisible(['email'])->toArray(),['password'=>'abcd1234']))
            ->assertResponseStatus(200)
            ->seeJson([
                'api_token'     =>  $user->api_token,
                'first_name'    =>  $user->first_name
            ]);

    }





    /** @test **/
    public function login_as_default_user_and_get_token()
    {   

        $this->post('authenticate',['password'=>'abcd1234','email'=>'admin@iserveu.ca'])
            ->assertResponseStatus(200)
            ->seeJson([
                'api_token' => User::first()->api_token
            ]);

    }




	/** @test **/
    public function login_with_correct_details()
    {	
        $faker = \Faker\Factory::create();

        $password = $faker->password;

        $user = factory(App\User::class)->create([
        	'password'	=> 	$password
        ]);

        $this->post( '/authenticate',['email' => $user->email,'password' => $password])
            ->assertResponseStatus(200)
            ->seeJson([
                'api_token' => $user->api_token
            ]);
    }


	/** @test **/
    public function login_fails_with_incorrect_password()
    {	
        $user = factory(App\User::class)->create();


        $this->post( '/authenticate',['email' => $user->email,'password' => "wrongpassword"])
             ->assertResponseStatus(403)
             ->seeJson([
                "error"     =>    "Invalid credentials",
                "message"   =>    "Either your username or password are incorrect"
            ]);
    }



    /** @test **/
    public function login_fails_with_non_existant_user()
    {   


        $this->post( '/authenticate',['password'=>'abcd1234','email'=>'notarealpersonatallhere@iserveu.ca'])
             ->assertResponseStatus(401)
             ->seeJson([
                "error"     =>    "Invalid credentials",
                "message"   =>    "This user does not exist"
            ]);

    }


    /** @test **/
    public function login_attempts_increment()
    {	
        $user = factory(App\User::class)->create();

        $this->seeInDatabase('users',array('id'=>$user->id,'login_attempts'=>0));

        $this->post( '/authenticate',['email' => $user->email,'password' => "wrongpassword1"]);
        $this->seeInDatabase('users',array('id'=>$user->id,'login_attempts'=>1));

        $this->post( '/authenticate',['email' => $user->email,'password' => "wrongpassword2"]);    
        $this->seeInDatabase('users',array('id'=>$user->id,'login_attempts'=>2));
    }

}
