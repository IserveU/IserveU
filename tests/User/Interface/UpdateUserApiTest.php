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
        $this->signInAsRole('administrator');
    }

    
    /** @test  ******************/
    public function update_user_with_email_and_password(){
        $this->updateFieldsGetSee(['email','password','first_name','last_name'],200);
        
    }

    /** @test  ******************/
    public function update_user_with_first_name(){
        $this->updateFieldsGetSee(['first_name','email','password','last_name'],200);
    }

    /** @test  ******************/
    public function update_user_with_middle_name(){
        $this->updateFieldsGetSee(['middle_name','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function update_user_with_last_name(){
        $this->updateFieldsGetSee(['last_name','email','password','first_name'],200);
    }

     /** @test  ******************/
    public function update_user_with_postal_code(){
         $this->updateFieldsGetSee(['postal_code','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function update_user_with_street_name(){
        $this->updateFieldsGetSee(['street_name','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function update_user_with_unit_number(){
        $this->updateFieldsGetSee(['unit_number','email','password','first_name','last_name'],200);        
    }

     /** @test  ******************/
    public function update_user_with_community_id(){
        $this->updateFieldsGetSee(['community_id','email','password','first_name','last_name'],200);
    }

    /** @test  ******************/
    public function update_user_with_status(){ // Need to change this to text
        $this->updateFieldsGetSee(['status','email','password','first_name','last_name'],200);        
    }

    /** @test  ******************/
    public function update_user_with_ethnic_origin_id(){ // May need to change this to text
        $this->updateFieldsGetSee(['ethnic_origin_id','email','password','first_name','last_name'],200);                
    }

    /** @test  ******************/
    public function update_user_with_date_of_birth(){ 
        $this->updateFieldsGetSee(['date_of_birth','ethnic_origin_id','email','password','first_name','last_name'],200);
        
    }

    /** @test  ******************/
    public function update_user_with_address_verified_until(){
        $this->updateFieldsGetSee(['address_verified_until','email','password','first_name','last_name'],200);                
    }


    /** @test  ******************/
    public function update_user_user_with_agreement_accepted(){
        $this->skipDatabaseCheck = ['agreement_accepted'];
        $this->updateContentGetSee([
            'agreement_accepted'=> 1
        ],200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES


    /** @test  ******************/
    public function update_user_with_invalid_email_fails(){
        $this->updateContentGetSee([
            'email'     => 'notareal.com'
        ],400);
    }

    /** @test  ******************/
    public function update_user_with_invalid_password_fails(){
        $this->updateContentGetSee([
            'password'     => "a"
        ],400);

        $this->updateContentGetSee([
            'password'     => "12345AA"
        ],400);
    }

    /** @test  ******************/
    public function update_user_with_empty_first_name_fails(){
        $this->updateContentGetSee([
            'first_name'     => ""
        ],400);        
    }


    /** @test  ******************/
    public function update_user_with_empty_last_name_fails(){
        $this->updateContentGetSee([
            'last_name'     => ""
        ],400);  
    }

    /** @test  ******************/
    public function update_user_with_invalid_postal_code_fails(){
        $this->updateContentGetSee([
            'postal_code'     => "no way this is a postal code"
        ],400); 
    }

     /** @test  ******************/
    public function update_user_with_non_numeric_street_number_fails(){
        $this->updateContentGetSee([
            'street_number'     => "notnumber"
        ],400);
    }

     /** @test  ******************/
    public function update_user_with_invalid_community_fails(){
        $this->updateContentGetSee([
            'community_id'     =>  0
        ],400);

      $this->updateContentGetSee([
            'community_id'     =>  9999999999999
        ],400);        

        $this->updateContentGetSee([
            'community_id'     =>  "Yellowknife"
        ],400);
    }

    /** @test  ******************/
    public function update_user_with_invalid_status_fails(){ // Need to change this to text
        $this->updateContentGetSee([
            'status'     =>  'notastatus'
        ],400);

        $this->updateContentGetSee([
            'status'     =>  2
        ],400);        
    }

    /** @test  ******************/
    public function update_user_with_invalid_ethnic_origin_id(){ // May need to change this to text
        $this->updateContentGetSee([
            'ethnic_origin_id'     =>  'notpossible'
        ],400);

        $this->updateContentGetSee([
            'ethnic_origin_id'     =>   2000
        ],400);         
    }

    /** @test  ******************/
    public function update_user_with_invalid_date_of_birth_fails(){ 
        $this->updateContentGetSee([
            'date_of_birth'     =>  'sdfsdf'
        ],400);

        $this->updateContentGetSee([
            'date_of_birth'     =>   9000
        ],400);             
    }

    /** @test  ******************/
    public function update_user_with_date_of_birth_in_future_fails(){

        $this->updateContentGetSee([
            'date_of_birth'     =>  \Carbon\Carbon::tomorrow()
        ],400);

        $this->updateContentGetSee([
            'date_of_birth'     =>  \Carbon\Carbon::tomorrow()->toDateString()
        ],400);               
    }

    /** @test  ******************/
    public function update_user_with_address_verified_until_in_past_fails(){
        $this->updateContentGetSee([
            'address_verified'     =>  \Carbon\Carbon::yesterday()
        ],400);

        $this->updateContentGetSee([
            'address_verified'     =>  \Carbon\Carbon::yesterday()->toDateString()
        ],400);
    }

    /** @test  ******************/
    public function update_user_with_agreement_accepted_date_fails(){

        $this->updateContentGetSee([
            'agreement_accepted'     =>  \Carbon\Carbon::yesterday()->toDateString()
        ],400);
    
    }

}
