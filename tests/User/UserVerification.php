<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserVerification extends TestCase
{
    use DatabaseTransactions;    
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();


        //Something
    }


    /*****************************************************************
    *
    *                   Privacy functions:
    *
    ******************************************************************/

    /** @test **/
    public function trigger_reverification(){
     
        $user = factory(App\User::class,'verified')->create();

        $this->seeInDatabase('users',array('id'=>$user->id,'updated_at'=>null));

        $this->signIn($user);
        $this->user->addUserRoleByName('citizen');

        $this->user->first_name = "My New Name";
        $this->user->save();

    }




}
