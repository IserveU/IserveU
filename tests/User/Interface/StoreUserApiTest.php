<?php
include_once('UserApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreUserApiTest extends UserApi
{
   
    use WithoutMiddleware;

    public function setUp()
    {   
        parent::setUp();
    }

    
    /** @test  ******************/
    public function store_user_with_email_and_password(){
        $this->storeUserWithFields(['email','password','first_name','last_name'],200);
        
    }

    /** @test  ******************/
    public function store_user_with_first_name(){
        $this->storeUserWithFields(['first_name','email','password','last_name'],200);
    }

    /** @test  ******************/
    public function store_user_with_middle_name(){
        $this->storeUserWithFields(['middle_name','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_last_name(){
        $this->storeUserWithFields(['last_name','email','password','first_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_postal_code(){
         $this->storeUserWithFields(['postal_code','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_street_name(){
        $this->storeUserWithFields(['street_name','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_unit_number(){
        $this->storeUserWithFields(['unit_number','email','password','first_name','last_name'],200);        
    }

     /** @test  ******************/
    public function store_user_with_community_id(){
        $this->storeUserWithFields(['community_id','email','password','first_name','last_name'],200);
    }

    /** @test  ******************/
    public function store_user_with_status(){ // Need to change this to text
        $this->storeUserWithFields(['status','email','password','first_name','last_name'],200);        
    }

    /** @test  ******************/
    public function store_user_with_ethnic_origin_id(){ // May need to change this to text
        $this->storeUserWithFields(['ethnic_origin_id','email','password','first_name','last_name'],200);                
    }

    /** @test  ******************/
    public function store_user_with_date_of_birth(){ 
        $this->storeUserWithFields(['date_of_birth','ethnic_origin_id','email','password','first_name','last_name'],200);
        
    }

    /** @test  ******************/
    public function store_user_with_address_verified_until(){
        $this->storeUserWithFields(['address_verified_until','email','password','first_name','last_name'],200);                
    }


    /////////////////////////////////////////////////////////// INCORRECT RESPONSES


   	/** @test  ******************/
    public function store_user_with_no_email_fails(){
        $this->storeUserWithContent([
            'email'     => ''
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_invalid_email_fails(){
        $this->storeUserWithContent([
            'email'     => 'notareal.com'
        ],400);
    }

	/** @test  ******************/
    public function store_user_with_no_password_fails(){
        $this->storeUserWithContent([
            'password'     => null
        ],400);

        $this->storeUserWithContent([
            'password'     => ""
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_invalid_password_fails(){
        $this->storeUserWithContent([
            'password'     => "a"
        ],400);

        $this->storeUserWithContent([
            'password'     => "12345AA"
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_empty_first_name_fails(){
        $this->storeUserWithContent([
            'first_name'     => null
        ],400);

        $this->storeUserWithContent([
            'first_name'     => ""
        ],400);        
    }


    /** @test  ******************/
    public function store_user_with_empty_last_name_fails(){
        $this->storeUserWithContent([
            'last_name'     => null
        ],400);

        $this->storeUserWithContent([
            'last_name'     => ""
        ],400);  
    }

    /** @test  ******************/
    public function store_user_with_invalid_postal_code_fails(){
        $this->storeUserWithContent([
            'postal_code'     => "no way this is a postal code"
        ],400); 
    }

     /** @test  ******************/
    public function store_user_with_non_numeric_street_number_fails(){
        $this->storeUserWithContent([
            'street_number'     => "notnumber"
        ],400);
    }


     /** @test  ******************/
    public function store_user_with_invalid_community_fails(){
        $this->storeUserWithContent([
            'community_id'     =>  0
        ],400);

      $this->storeUserWithContent([
            'community_id'     =>  9999999999999
        ],400);        

        $this->storeUserWithContent([
            'community_id'     =>  "Yellowknife"
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_invalid_status_fails(){ // Need to change this to text
        $this->storeUserWithContent([
            'status'     =>  'notastatus'
        ],400);

        $this->storeUserWithContent([
            'status'     =>  2
        ],400);        
    }

    /** @test  ******************/
    public function store_user_with_invalid_ethnic_origin_id(){ // May need to change this to text
        $this->storeUserWithContent([
            'ethnic_origin_id'     =>  'notpossible'
        ],400);

        $this->storeUserWithContent([
            'ethnic_origin_id'     =>   2000
        ],400);         
    }

    /** @test  ******************/
    public function store_user_with_invalid_date_of_birth_fails(){ 
        $this->storeUserWithContent([
            'date_of_birth'     =>  'sdfsdf'
        ],400);

        $this->storeUserWithContent([
            'date_of_birth'     =>   9000
        ],400);             
    }

    /** @test  ******************/
    public function store_user_with_date_of_birth_in_future_fails(){

        $this->storeUserWithContent([
            'date_of_birth'     =>  \Carbon\Carbon::tomorrow()
        ],400);

        $this->storeUserWithContent([
            'date_of_birth'     =>  \Carbon\Carbon::tomorrow()->toDateString()
        ],400);               
    }

    /** @test  ******************/
    public function store_user_with_address_verified_until_in_past_fails(){
        $this->storeUserWithContent([
            'address_verified'     =>  \Carbon\Carbon::yesterday()
        ],400);

        $this->storeUserWithContent([
            'address_verified'     =>  \Carbon\Carbon::yesterday()->toDateString()
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_agreement_accepted_fails(){
        $this->storeUserWithContent([
            'agreement_accepted'     =>  \Carbon\Carbon::today()
        ],400);        

        $this->storeUserWithContent([
            'agreement_accepted'     =>  \Carbon\Carbon::yesterday()
        ],400);

        $this->storeUserWithContent([
            'agreement_accepted'     =>  \Carbon\Carbon::tomorrow()
        ],400);     
    }

}
