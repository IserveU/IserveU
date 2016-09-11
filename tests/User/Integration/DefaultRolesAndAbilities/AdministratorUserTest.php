<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdministratorUserTest extends TestCase
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
        $this->user->addUserRoleByName('administrator');
    }

    /*****************************************************************
    *
    *                   Privacy functions:
    *
    ******************************************************************/

    /** @test **/
    public function show_public_user(){
        $user = factory(App\User::class,'public')->create();

        $this->get('/api/user/'.$user->id);

        $this->assertResponseStatus(200);

        $this->see($user->first_name);
        $this->see($user->last_name);

    }

    /** @test **/
    public function show_private_user(){
        $user = factory(App\User::class,'private')->create();

        $this->get('/api/user/'.$user->id);

        $this->assertResponseStatus(200);

        $this->see($user->first_name);
        $this->see($user->last_name);
    }



    /*****************************************************************
    *
    *                   Update / Edit
    *
    ******************************************************************/
 
    /** @test **/
    public function verify_user_identity(){
        $user = factory(App\User::class,'private')->create([
            'identity_verified' => 0
        ]);

        $this->seeInDatabase('users',['id'=>$user->id,'identity_verified'=>0]);

        $this->patch('/api/user/'.$user->id,['identity_verified'=>1]);

        $this->assertResponseStatus(200);

        $this->seeInDatabase('users',['id'=>$user->id,'identity_verified'=>1]);
    }


        /** @test **/
    public function verify_user_address(){
        $user = factory(App\User::class,'private')->create();

        $this->seeInDatabase('users',['id'=>$user->id,'address_verified_until'=>null]);

        $verifyUntilDate = \Carbon\Carbon::now()->addDays(1200)->toIso8601String();

        $this->patch('/api/user/'.$user->id,['address_verified_until'=>$verifyUntilDate]);


        $this->assertResponseStatus(200);



        $this->notSeeInDatabase('users',['id'=>$user->id,'address_verified_until'=>null]);
    }



}
