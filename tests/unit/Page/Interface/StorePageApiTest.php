<?php
include_once('PageApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StorePageApiTest extends PageApi
{
   
    use DatabaseTransactions;    

    public function setUp()
    {   
        parent::setUp();
        $this->signInAsRole('administrator');
    }

    
    /** @test  ******************/
    public function store_page_with_title(){
        $this->storeFieldsGetSee(['title'],200);
        
    }


    /** @test  ******************/
    public function store_page_with_content(){
        $this->storeFieldsGetSee(['title','content'],200);
    }


    /////////////////////////////////////////////////////////// INCORRECT RESPONSES


   	/** @test  ******************/
    public function store_page_with_empty_title_fails(){
        $this->storeContentGetSee([
            'title'     => ''
        ],400);
        
    }


    /** @test  ******************/
    public function store_page_title_with_an_array_fails(){
        $this->storeContentGetSee([
            'title'     => ['titles']
        ],400);
    }


    /** @test  ******************/
    public function store_page_content_as_array_fails(){
        $this->storeContentGetSee([
            'title'     => ['titles']
        ],400);
    }


    /** @test  ******************/
    public function store_page_slug_fails(){
        $this->storeContentGetSee([
            'slug'     => 'cant-set-it-yourself'
        ],400);
    }



}
