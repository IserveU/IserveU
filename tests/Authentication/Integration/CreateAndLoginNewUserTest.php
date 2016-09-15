<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateAndLoginNewUserTest extends TestCase
{
    use DatabaseTransactions;



	/** @test **/
    public function register_self_as_new_user_and_get_details()
    {
        $this->setSettings(['security.verify_citizens'=>0]);
        $user = factory(App\User::class)->make()->skipVisibility()->setVisible(['first_name','last_name','email'])->toArray();

        $user['password'] = 'abcd1234';
      

        $this->post('/api/user',$user)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'permissions',
                'remember_token'
            ]);

        $this->seeInDatabase('users',array('email'=>$user['email'],'first_name'=>$user['first_name']));

    }



    /** @test **/
    public function check_instant_citizen_verify_setting_on()
    {   
        $this->setSettings(['security.verify_citizens'=>false]);
        
        $user = factory(App\User::class)->make()->skipVisibility()->setVisible(['first_name','last_name','email'])->toArray();

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
        
        $user = factory(App\User::class)->make()->skipVisibility()->setVisible(['first_name','last_name','email'])->toArray();

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
