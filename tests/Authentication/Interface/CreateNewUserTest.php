<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateNewUser extends TestCase
{
    use DatabaseTransactions;


	/** @test **/
    public function register_self_as_new_user_details()
    {
        $this->setSettings(['security.verify_citizens'=>0]);
        $user = factory(App\User::class)->make()->setVisible(['first_name','last_name','email','password'])->toArray();

        $user['password'] = 'abcd1234';
      

        $this->post('/api/user',$user)
            ->assertResponseStatus(200);

        $this->seeInDatabase('users',array('email'=>$user['email'],'first_name'=>$user['first_name']));

    }


    /** @test **/
    public function login_as_minimal_specs_new_user()
    {   
        $user = factory(App\User::class)->create([
            'password'  => "abcd1234!"
        ]);

        $this->post('/authenticate',['email'=>$user->email,'password'=>'abcd1234!'])
             ->assertResponseStatus(200)
             ->seeJson([
                'api_token' => $user->api_token
             ]);
    }

    /** @test **/
    public function try_to_register_user_with_duplicate_email_address()
    {   

        $user = factory(App\User::class)->create();

        $this->post('/api/user',$user->setVisible(['first_name','last_name','email','password'])->toArray());

        $this->assertResponseStatus(400);
 
    }

    /** @test **/
    public function check_instant_citizen_verify_setting_on()
    {   
        $this->setSettings(['security.verify_citizens'=>false]);
        
        $user = factory(App\User::class)->make()->setVisible(['first_name','last_name','email'])->toArray();

        $user['password']   =  'abcd1234';

        $this->post('/api/user',$user)
             ->assertResponseStatus(200)
             ->seeInDatabase('users',array('first_name'=>$user['first_name']));

        $apiToken = json_decode($this->response->getContent())->api_token;

        $user = getUserWithToken($apiToken);


        $citizenRole = \App\Role::where('name','citizen')->first();

        $this->seeInDatabase('role_user',array('user_id'=>$user->id,'role_id'=>$citizenRole->id));
    }

    /** @test **/
    public function check_instant_citizen_verify_setting_off()
    {   
        $this->setSettings(['security.verify_citizens'=>true]);

        $user = factory(App\User::class)->make()->setVisible(['first_name','last_name','email'])->toArray();

        $user['password']   =  'abcd1234';

        $this->post('/api/user',$user)
             ->assertResponseStatus(200)
             ->seeInDatabase('users',array('first_name'=>$user['first_name']));

        $apiToken = json_decode($this->response->getContent())->api_token;

        $user = getUserWithToken($apiToken);


        $citizenRole = \App\Role::where('name','citizen')->first();

        $this->dontSeeInDatabase('role_user',array('user_id'=>$user->id,'role_id'=>$citizenRole->id));
    }



}
