<?php
include_once('DepartmentApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexDepartmentApiTest extends DepartmentApi
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES 

    /** @test */
    public function default_department_filter(){
        $this->get($this->route)->dump();
        
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
