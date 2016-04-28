<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JWT extends TestCase
{
    use WithoutMiddleware;
    
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



    /** @test **/
    public function create_and_login_as_minimal_specs_new_user()
    {   
        $user = factory(App\User::class)->make();
      
        $this->post('/api/user',array_merge($user->setVisible(['first_name','last_name','email'])->toArray(),['password'=>'abcd1234']));

        $this->assertResponseStatus(200);

        $this->post('authenticate',['email'=>$user->email,'password'=>'abcd1234']);

        
        $content = json_decode($this->response->getContent());
        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');
    }

}
