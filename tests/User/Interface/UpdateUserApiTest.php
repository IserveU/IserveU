<?php
include_once('UserApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateUserApiTest extends UserApi
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();
    }

    
    /** @test  ******************/
    public function update_user_with_email_and_password(){
        $this->updateUserWithFields(['email','password','first_name','last_name'],200);
        
    }

    /** @test  ******************/
    public function update_user_with_first_name(){
        $this->updateUserWithFields(['first_name','email','password','last_name'],200);
    }

    /** @test  ******************/
    public function update_user_with_middle_name(){
        $this->updateUserWithFields(['middle_name','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function update_user_with_last_name(){
        $this->updateUserWithFields(['last_name','email','password','first_name'],200);
    }

     /** @test  ******************/
    public function update_user_with_postal_code(){
         $this->updateUserWithFields(['postal_code','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function update_user_with_street_name(){
        $this->updateUserWithFields(['street_name','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function update_user_with_unit_number(){
        $this->updateUserWithFields(['unit_number','email','password','first_name','last_name'],200);        
    }

     /** @test  ******************/
    public function update_user_with_community_id(){
        $this->updateUserWithFields(['community_id','email','password','first_name','last_name'],200);
    }

    /** @test  ******************/
    public function update_user_with_status(){ // Need to change this to text
        $this->updateUserWithFields(['status','email','password','first_name','last_name'],200);        
    }

    /** @test  ******************/
    public function update_user_with_ethnic_origin_id(){ // May need to change this to text
        $this->updateUserWithFields(['ethnic_origin_id','email','password','first_name','last_name'],200);                
    }

    /** @test  ******************/
    public function update_user_with_date_of_birth(){ 
        $this->updateUserWithFields(['date_of_birth','ethnic_origin_id','email','password','first_name','last_name'],200);
        
    }

    /** @test  ******************/
    public function update_user_with_address_verified_until(){
        $this->updateUserWithFields(['address_verified_until','email','password','first_name','last_name'],200);                
    }


    /////////////////////////////////////////////////////////// INCORRECT RESPONSES


    /** @test  ******************/
    public function update_user_with_invalid_email_fails(){
        $this->updateUserWithContent([
            'email'     => 'notareal.com'
        ],400);
    }

    /** @test  ******************/
    public function update_user_with_invalid_password_fails(){
        $this->updateUserWithContent([
            'password'     => "a"
        ],400);

        $this->updateUserWithContent([
            'password'     => "12345AA"
        ],400);
    }

    /** @test  ******************/
    public function update_user_with_empty_first_name_fails(){
        $this->updateUserWithContent([
            'first_name'     => ""
        ],400);        
    }


    /** @test  ******************/
    public function update_user_with_empty_last_name_fails(){
        $this->updateUserWithContent([
            'last_name'     => ""
        ],400);  
    }

    /** @test  ******************/
    public function update_user_with_invalid_postal_code_fails(){
        $this->updateUserWithContent([
            'postal_code'     => "no way this is a postal code"
        ],400); 
    }

     /** @test  ******************/
    public function update_user_with_non_numeric_street_number_fails(){
        $this->updateUserWithContent([
            'street_number'     => "notnumber"
        ],400);
    }

     /** @test  ******************/
    public function update_user_with_invalid_community_fails(){
        $this->updateUserWithContent([
            'community_id'     =>  0
        ],400);

      $this->updateUserWithContent([
            'community_id'     =>  9999999999999
        ],400);        

        $this->updateUserWithContent([
            'community_id'     =>  "Yellowknife"
        ],400);
    }

    /** @test  ******************/
    public function update_user_with_invalid_status_fails(){ // Need to change this to text
        $this->updateUserWithContent([
            'status'     =>  'notastatus'
        ],400);

        $this->updateUserWithContent([
            'status'     =>  2
        ],400);        
    }

    /** @test  ******************/
    public function update_user_with_invalid_ethnic_origin_id(){ // May need to change this to text
        $this->updateUserWithContent([
            'ethnic_origin_id'     =>  'notpossible'
        ],400);

        $this->updateUserWithContent([
            'ethnic_origin_id'     =>   2000
        ],400);         
    }

    /** @test  ******************/
    public function update_user_with_invalid_date_of_birth_fails(){ 
        $this->updateUserWithContent([
            'date_of_birth'     =>  'sdfsdf'
        ],400);

        $this->updateUserWithContent([
            'date_of_birth'     =>   9000
        ],400);             
    }

    /** @test  ******************/
    public function update_user_with_date_of_birth_in_future_fails(){

        $this->updateUserWithContent([
            'date_of_birth'     =>  \Carbon\Carbon::tomorrow()
        ],400);

        $this->updateUserWithContent([
            'date_of_birth'     =>  \Carbon\Carbon::tomorrow()->toDateString()
        ],400);               
    }

    /** @test  ******************/
    public function update_user_with_address_verified_until_in_past_fails(){
        $this->updateUserWithContent([
            'address_verified'     =>  \Carbon\Carbon::yesterday()
        ],400);

        $this->updateUserWithContent([
            'address_verified'     =>  \Carbon\Carbon::yesterday()->toDateString()
        ],400);
    }

    /** @test  ******************/
    public function update_user_with_agreement_accepted_in_past_fails(){

        $this->updateUserWithContent([
            'agreement_accepted'     =>  \Carbon\Carbon::yesterday()->toDateString()
        ],400);
    
    }

}
