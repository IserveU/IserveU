<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateUserApiTest extends TestCase
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();
    }

    // DELETE Government ID, change "Community Id" to a string and input filtering for the string
    
    // Convert public boolean to status string
    // Convert public boolean to status string
    
    /** @test  ******************/
    public function update_user_with_email_and_password(){
        
    }

    /** @test  ******************/
    public function update_user_with_first_name(){
        
    }

    /** @test  ******************/
    public function update_user_with_middle_name(){
        
    }

     /** @test  ******************/
    public function update_user_with_last_name(){
        
    }

     /** @test  ******************/
    public function update_user_with_postal_code(){
        
    }

    /** @test  ******************/
    public function update_user_with_street_name(){
        
    }

    /** @test  ******************/
    public function update_user_with_street_number(){
        
    }

     /** @test  ******************/
    public function update_user_with_unit_number(){
        
    }

     /** @test  ******************/
    public function update_user_with_community_id(){
        
    }

    /** @test  ******************/
    public function update_user_with_status(){ // Need to change this to text
        
    }

    /** @test  ******************/
    public function update_user_with_ethnic_origin_id(){ // May need to change this to text
        
    }

    /** @test  ******************/
    public function update_user_with_date_of_birth(){ 
        
    }

    /** @test  ******************/
    public function update_user_with_address_verified_until(){
        
    }

    /** @test  ******************/
    public function update_user_with_agreement_accepted(){
        
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES



    /** @test  ******************/
    public function update_user_with_invalid_email_fails(){
        
    }

    /** @test  ******************/
    public function update_user_with_empty_first_name_fails(){
        
    }

    /** @test  ******************/
    public function update_user_with_empty_middle_name_fails(){
        
    }

    /** @test  ******************/
    public function update_user_with_empty_last_name_fails(){
        
    }

    /** @test  ******************/
    public function update_user_with_invalid_postal_code_fails(){
        
    }

     /** @test  ******************/
    public function update_user_with_non_numeric_street_number_fails(){
        
    }


     /** @test  ******************/
    public function update_user_with_invalid_community_fails(){
        
    }

    /** @test  ******************/
    public function update_user_with_invalid_status(){ // Need to change this to text
        
    }

    /** @test  ******************/
    public function update_user_with_ethnic_origin_id(){ // May need to change this to text
        
    }

    /** @test  ******************/
    public function update_user_with_invalid_date_of_birth_fails(){ 
        
    }

    /** @test  ******************/
    public function update_user_with_date_of_birth_in_future_fails(){ 
        
    }

    /** @test  ******************/
    public function update_user_with_address_verified_until_in_past_fails(){
        
    }

    /** @test  ******************/
    public function update_user_with_agreement_not_accepted_fails(){
        
    }

}
