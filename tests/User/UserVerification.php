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
    public function change_first_name_triggers_reverification(){

        $this->setSettings(['security.verify_citizens'=>true]);
        
     
        $user = factory(App\User::class,'verified')->create();
        $user->addUserRoleByName('citizen');

        $this->assertEquals($user->identity_verified,1);
        $this->assertEquals($user->hasRole('citizen'),true);


        $this->signIn($user);

        $user->first_name = "My New Name";
        $this->user->save();

        $user->fresh();

        $this->assertEquals($user->identity_verified,0);
        $this->assertEquals($user->hasRole('citizen'),false);

        
    }


    /** @test **/
    public function change_last_name_triggers_reverification(){

        $this->setSettings(['security.verify_citizens'=>true]);
        
     
        $user = factory(App\User::class,'verified')->create();
        $user->addUserRoleByName('citizen');

        $this->assertEquals($user->identity_verified,1);
        $this->assertEquals($user->hasRole('citizen'),true);


        $this->signIn($user);

        $user->last_name = "My Last Name";
        $this->user->save();

        $user->fresh();

        $this->assertEquals($user->identity_verified,0);
        $this->assertEquals($user->hasRole('citizen'),false);

    }


    /** @test **/
    public function change_birthdate_triggers_reverification(){

        $this->setSettings(['security.verify_citizens'=>true]);
        
     
        $user = factory(App\User::class,'verified')->create();
        $user->addUserRoleByName('citizen');

        $this->assertEquals($user->identity_verified,1);
        $this->assertEquals($user->hasRole('citizen'),true);


        $this->signIn($user);

        $user->date_of_birth = \Carbon\Carbon::now();// "1990-01-06";
        $this->user->save();

        $user->fresh();

        $this->assertEquals($user->identity_verified,0);
        $this->assertEquals($user->hasRole('citizen'),false);

    }


}
