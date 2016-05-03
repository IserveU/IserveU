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

        $user = factory(App\User::class,'verified')->create();
        $this->signIn($user);
        $this->user->addUserRoleByName('citizen');

        //Something
    }


    /*****************************************************************
    *
    *                   Privacy functions:
    *
    ******************************************************************/

    /** @test **/
    public function trigger_reverification(){
     
        $this->user->first_name = "My New Name";
        $this->user->save();

    }




}
