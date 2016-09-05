<?php
include_once('MotionApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreMotionApiTest extends MotionApi
{
   
    use WithoutMiddleware;

    public function setUp()
    {   
        parent::setUp();
        $this->signInAsRole('administrator');
    }

    
    /** @test  ******************/
    public function store_motion_with_title(){
        $this->storeFieldsGetSee(['title','department_id'],200);
        
    }

    /** @test  ******************/
    public function store_motion_with_summary(){
        $this->storeFieldsGetSee(['title','department_id','summary'],200);
    }

    /** @test  ******************/
    public function store_motion_with_text(){
        $this->storeFieldsGetSee(['title','department_id','text'],200);
    }

     /** @test  ******************/
    public function store_motion_with_department_id(){
        $this->storeFieldsGetSee(['title','department_id','department_id'],200);
    }

     /** @test  ******************/
    public function store_motion_with_closing(){
         $this->storeFieldsGetSee(['title','department_id','closing'],200);
    }

     /** @test  ******************/
    public function store_motion_with_user_id(){
        $this->storeFieldsGetSee(['title','department_id','user_id'],200);
    }

     /** @test  ******************/
    public function store_motion_with_status(){
        $this->storeFieldsGetSee(['title','department_id','status'],200);        
    }

     /** @test  ******************/
    public function store_motion_with_budget(){
        $this->storeFieldsGetSee(['title','department_id','budget'],200);        
    }


    /////////////////////////////////////////////////////////// INCORRECT RESPONSES


   	/** @test  ******************/
    public function store_motion_with_empty_title_fails(){
        $this->storeContentGetSee([
            'title'     => ''
        ],400);
     
    }


    /** @test  ******************/
    public function store_motion_title_with_an_array_fails(){
        $this->storeContentGetSee([
            'title'     => ['titles']
        ],400);
    }

    /** @test  ******************/
    public function store_motion_summary_with_an_array_fails(){
        $this->storeContentGetSee([
            'summary'     => ['the summary']
        ],400);
    }

	/** @test  ******************/
    public function store_motion_with_invalid_department_fails(){
        $this->storeContentGetSee([
            'department_id'     => "An apartment ID"
        ],400);

        $this->storeContentGetSee([
            'department_id'     => 9000
        ],400);
    }


    /** @test  ******************/
    public function store_motion_with_closing_in_past_fails(){
        $this->storeContentGetSee([
            'closing'     =>  \Carbon\Carbon::yesterday()
        ],400);

        $this->storeContentGetSee([
            'closing'     =>  \Carbon\Carbon::yesterday()->toDateString()
        ],400);
    }

    /** @test  ******************/
    public function store_motion_with_invalid_user_fails(){
        $this->storeContentGetSee([
            'user_id'     =>  99999999
        ],400);

        $this->storeContentGetSee([
            'user_id'     =>  "Jessica Tran 9000!"
        ],400);
    }

    /** @test  ******************/
    public function store_motion_with_invalid_status_fails(){
        $this->storeContentGetSee([
            'status'     =>  99999999
        ],400);

        $this->storeContentGetSee([
            'status'     =>  "Open"
        ],400);
    }



}
