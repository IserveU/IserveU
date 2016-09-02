<?php
include_once('UserApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class storeApiTest extends UserApi
{
   
    use WithoutMiddleware;

    public function setUp()
    {   
        parent::setUp();


    }

    
    /** @test  ******************/
    public function store_user_with_email_and_password(){
        $this->storeFieldsGetSee(['email','password','first_name','last_name'],200);
        
    }

    /** @test  ******************/
    public function store_user_with_first_name(){
        $this->storeFieldsGetSee(['first_name','email','password','last_name'],200);
    }

    /** @test  ******************/
    public function store_user_with_middle_name(){
        $this->storeFieldsGetSee(['middle_name','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_last_name(){
        $this->storeFieldsGetSee(['last_name','email','password','first_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_postal_code(){
         $this->storeFieldsGetSee(['postal_code','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_street_name(){
        $this->storeFieldsGetSee(['street_name','email','password','first_name','last_name'],200);
    }

     /** @test  ******************/
    public function store_user_with_unit_number(){
        $this->storeFieldsGetSee(['unit_number','email','password','first_name','last_name'],200);        
    }

     /** @test  ******************/
    public function store_user_with_community_id(){
        $this->storeFieldsGetSee(['community_id','email','password','first_name','last_name'],200);
    }

    /** @test  ******************/
    public function store_user_with_status(){ // Need to change this to text
        $this->storeFieldsGetSee(['status','email','password','first_name','last_name'],200);        
    }

    /** @test  ******************/
    public function store_user_with_ethnic_origin_id(){ // May need to change this to text
        $this->storeFieldsGetSee(['ethnic_origin_id','email','password','first_name','last_name'],200);                
    }

    /** @test  ******************/
    public function store_user_with_date_of_birth(){ 
        $this->storeFieldsGetSee(['date_of_birth','ethnic_origin_id','email','password','first_name','last_name'],200);
        
    }

    /** @test  ******************/
    public function store_user_with_address_verified_until(){
        $this->storeFieldsGetSee(['address_verified_until','email','password','first_name','last_name'],200);                
    }


    /////////////////////////////////////////////////////////// INCORRECT RESPONSES


   	/** @test  ******************/
    public function store_user_with_no_email_fails(){
        $this->storeContentGetSee([
            'email'     => ''
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_invalid_email_fails(){
        $this->storeContentGetSee([
            'email'     => 'notareal.com'
        ],400);
    }

	/** @test  ******************/
    public function store_user_with_no_password_fails(){
        $this->storeContentGetSee([
            'password'     => null
        ],400);

        $this->storeContentGetSee([
            'password'     => ""
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_invalid_password_fails(){
        $this->storeContentGetSee([
            'password'     => "a"
        ],400);

        $this->storeContentGetSee([
            'password'     => "12345AA"
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_empty_first_name_fails(){
        $this->storeContentGetSee([
            'first_name'     => null
        ],400);

        $this->storeContentGetSee([
            'first_name'     => ""
        ],400);        
    }


    /** @test  ******************/
    public function store_user_with_empty_last_name_fails(){
        $this->storeContentGetSee([
            'last_name'     => null
        ],400);

        $this->storeContentGetSee([
            'last_name'     => ""
        ],400);  
    }

    /** @test  ******************/
    public function store_user_with_invalid_postal_code_fails(){
        $this->storeContentGetSee([
            'postal_code'     => "no way this is a postal code"
        ],400); 
    }

     /** @test  ******************/
    public function store_user_with_non_numeric_street_number_fails(){
        $this->storeContentGetSee([
            'street_number'     => "notnumber"
        ],400);
    }


     /** @test  ******************/
    public function store_user_with_invalid_community_fails(){
        $this->storeContentGetSee([
            'community_id'     =>  0
        ],400);

      $this->storeContentGetSee([
            'community_id'     =>  9999999999999
        ],400);        

        $this->storeContentGetSee([
            'community_id'     =>  "Yellowknife"
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_invalid_status_fails(){ // Need to change this to text
        $this->storeContentGetSee([
            'status'     =>  'notastatus'
        ],400);

        $this->storeContentGetSee([
            'status'     =>  2
        ],400);        
    }

    /** @test  ******************/
    public function store_user_with_invalid_ethnic_origin_id(){ // May need to change this to text
        $this->storeContentGetSee([
            'ethnic_origin_id'     =>  'notpossible'
        ],400);

        $this->storeContentGetSee([
            'ethnic_origin_id'     =>   2000
        ],400);         
    }

    /** @test  ******************/
    public function store_user_with_invalid_date_of_birth_fails(){ 
        $this->storeContentGetSee([
            'date_of_birth'     =>  'sdfsdf'
        ],400);

        $this->storeContentGetSee([
            'date_of_birth'     =>   9000
        ],400);             
    }

    /** @test  ******************/
    public function store_user_with_date_of_birth_in_future_fails(){

        $this->storeContentGetSee([
            'date_of_birth'     =>  \Carbon\Carbon::tomorrow()
        ],400);

        $this->storeContentGetSee([
            'date_of_birth'     =>  \Carbon\Carbon::tomorrow()->toDateString()
        ],400);               
    }

    /** @test  ******************/
    public function store_user_with_address_verified_until_in_past_fails(){
        $this->storeContentGetSee([
            'address_verified'     =>  \Carbon\Carbon::yesterday()
        ],400);

        $this->storeContentGetSee([
            'address_verified'     =>  \Carbon\Carbon::yesterday()->toDateString()
        ],400);
    }

    /** @test  ******************/
    public function store_user_with_agreement_accepted_fails(){
        $this->storeContentGetSee([
            'agreement_accepted'     =>  \Carbon\Carbon::today()
        ],400);        

        $this->storeContentGetSee([
            'agreement_accepted'     =>  \Carbon\Carbon::yesterday()
        ],400);

        $this->storeContentGetSee([
            'agreement_accepted'     =>  \Carbon\Carbon::tomorrow()
        ],400);     
    }

}
