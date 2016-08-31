<?php
include('UserApiTests.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreUserApiTest extends UserApiTests
{
    use DatabaseTransactions;    
    use WithoutMiddleware;

    public function setUp()
    {   
        parent::setUp();
    }



    // DELETE Government ID, change "Community Id" to a string and input filtering for the string
    
    // Convert public boolean to status string
    // Convert public boolean to status string
    
    /** @test  ******************/
    public function store_user_with_email_and_password(){
        $this->storeUserWithFields(['email','password'],200);
        
    }

    /** @test  ******************/
    public function store_user_with_first_name(){
        $this->storeUserWithFields(['first_name'],200);
    }

    /** @test  ******************/
    public function store_user_with_middle_name(){
        $this->storeUserWithFields(['middle_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_last_name(){
        $this->storeUserWithFields(['last_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_postal_code(){
         $this->storeUserWithFields(['postal_code'],200);
    }

     /** @test  ******************/
    public function store_user_with_street_name(){
        $this->storeUserWithFields(['street_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_unit_number(){
        $this->storeUserWithFields(['unit_number'],200);        
    }

     /** @test  ******************/
    public function store_user_with_community_id(){
        $this->storeUserWithFields(['community_id'],200);
    }

    /** @test  ******************/
    public function store_user_with_status(){ // Need to change this to text
        $this->storeUserWithFields(['status'],200);        
    }

    /** @test  ******************/
    public function store_user_with_ethnic_origin_id(){ // May need to change this to text
        $this->storeUserWithFields(['ethnic_origin_id'],200);                
    }

    /** @test  ******************/
    public function store_user_with_date_of_birth(){ 
        $this->storeUserWithFields(['ethnic_origin_id'],200);                
        
    }

    /** @test  ******************/
    public function store_user_with_address_verified_until(){
        
    }

    /** @test  ******************/
    public function store_user_with_agreement_accepted(){
        
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES


   	/** @test  ******************/
    public function store_user_with_no_email_fails(){
        
    }

    /** @test  ******************/
    public function store_user_with_invalid_email_fails(){
        
    }

	/** @test  ******************/
    public function store_user_with_no_password_fails(){
        
    }

    /** @test  ******************/
    public function store_user_with_invalid_password_fails(){
        
    }

    /** @test  ******************/
    public function store_user_with_empty_first_name_fails(){
        
    }

    /** @test  ******************/
    public function store_user_with_empty_middle_name_fails(){
        
    }

    /** @test  ******************/
    public function store_user_with_empty_last_name_fails(){
        
    }

    /** @test  ******************/
    public function store_user_with_invalid_postal_code_fails(){
        
    }

     /** @test  ******************/
    public function store_user_with_non_numeric_street_number_fails(){
        
    }


     /** @test  ******************/
    public function store_user_with_invalid_community_fails(){
        
    }

    /** @test  ******************/
    public function store_user_with_invalid_status(){ // Need to change this to text
        
    }

    /** @test  ******************/
    public function store_user_with_invalid_ethnic_origin_id(){ // May need to change this to text
        
    }

    /** @test  ******************/
    public function store_user_with_invalid_date_of_birth_fails(){ 
        
    }

    /** @test  ******************/
    public function store_user_with_date_of_birth_in_future_fails(){ 
        
    }

    /** @test  ******************/
    public function store_user_with_address_verified_until_in_past_fails(){
        
    }

    /** @test  ******************/
    public function store_user_with_agreement_accepted_fails(){
        
    }

}
