<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateNewUser extends TestCase
{


    public function setUp(){
        parent::setUp();
    }

    public function tearDown(){
        $this->restoreSettings();
        parent::tearDown();
    }


	/** @test **/
    public function submit_new_user_details()
    {	
     
        $user = factory(App\User::class)->make();

        $this->post('/api/user',$user->setVisible(['first_name','last_name','email','password'])->toArray());

        $this->assertResponseStatus(200);

      //  $this->expectsEvents(\App\Events\User\UserCreated::class);
        $content = json_decode($this->response->getContent());
   
        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');
        $this->seeInDatabase('users',array('email'=>$user->email,'first_name'=>$user->first_name));

    }

    /** @test **/
    public function login_as_new_user()
    {   

        $user = factory(App\User::class)->create([
            'password'  => 'abcd1234'
        ]);


        $this->post('authenticate',array_merge($user->setVisible(['email'])->toArray(),['password'=>'abcd1234']));

        $content = json_decode($this->response->getContent());
        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');
    }

    /** @test **/
    public function login_as_minimal_specs_new_user()
    {   
        $userSpecs = factory(App\User::class)->make()->setVisible(['email','first_name','last_name'])->toArray();
        $user = factory(App\User::class)->create(array_merge($userSpecs,['password'=>'abcd1234']));

        $this->post('authenticate',['email'=>$user->email,'password'=>'abcd1234']);
        $content = json_decode($this->response->getContent());
        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');
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
        

        $user = factory(App\User::class)->make();
        $this->post('/api/user',$user->setVisible(['first_name','last_name','email','password'])->toArray());

        $this->assertResponseStatus(200);

        $content = json_decode($this->response->getContent());

        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');
        $this->seeInDatabase('users',array('id'=>$content->user->id));

        $citizenRole = \App\Role::where('name','citizen')->first();

        $this->seeInDatabase('role_user',array('user_id'=>$content->user->id,'role_id'=>$citizenRole->id));
    }

    /** @test **/
    public function check_instant_citizen_verify_setting_off()
    {   
        $this->setSettings(['security.verify_citizens'=>true]);
        

        $user = factory(App\User::class)->make();
        $this->post('/api/user',$user->setVisible(['first_name','last_name','email','password'])->toArray());

        $this->assertResponseStatus(200);

        $content = json_decode($this->response->getContent());

        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');
        $this->seeInDatabase('users',array('id'=>$content->user->id));

        $citizenRole = \App\Role::where('name','citizen')->first();

        $this->dontSeeInDatabase('role_user',array('user_id'=>$content->user->id,'role_id'=>$citizenRole->id));
    }
}
