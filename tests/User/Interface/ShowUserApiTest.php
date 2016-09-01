<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowUserApiTest extends TestCase
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();
    }

    
    /////////////////////////////////////////////////////////// CORRECT RESPONSES
   
    /** @test */
    public function show_user_test(){
        $this->signIn();

        $this->visit("/api/user/".$this->user->id)
            ->seeJsonStructure([
                'email','ethnic_origin_id','first_name','middle_name','last_name','date_of_birth','postal_code', 'street_name', 'street_number', 'unit_number','agreement_accepted', 'community_id','identity_verified', 'address_verified_until','status'

            ])->dontSeeJson([
                'password',
                'created_at',
                'api_token'

            ]);
        



    }

}
