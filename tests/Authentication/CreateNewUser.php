<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateNewUser extends TestCase
{


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

        // New users should have 0 roles
        $this->dontSeeInDatabase('role_user',array('user_id'=>$content->user->id));
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



}
