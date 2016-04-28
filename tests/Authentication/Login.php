<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Login extends TestCase
{
	

	/** @test **/
    public function login_with_correct_details()
    {	
        $faker = \Faker\Factory::create();

        $password = $faker->password;

        $user = factory(App\User::class)->create([
        	'password'	=> 	$password
        ]);

        $this->post( '/authenticate',['email' => $user->email,'password' => $password]);

        $content = json_decode($this->response->getContent());
   
        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');
    }


	/** @test **/
    public function login_fails_with_incorrect_password()
    {	
        $user = factory(App\User::class)->create();

        $this->post( '/authenticate',['email' => $user->email,'password' => "wrongpassword"]);
        $this->assertResponseStatus(401);
        $content = json_decode($this->response->getContent());

        $this->assertEquals($content, null);

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


    /** @test **/
    public function create_and_login_as_minimal_specs_new_user()
    {   
        $user = factory(App\User::class)->make();
      
        $this->post('/api/user',array_merge($user->setVisible(['first_name','last_name','email'])->toArray(),['password'=>'abcd1234']));

        $this->assertResponseStatus(200);

        $this->post('authenticate',['email'=>$user->email,'password'=>'abcd1234']);

        $content = json_decode($this->response->getContent());
        
        if(!$content){
            dd($this->response->getContent());
        }

        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');
    }
    
}
