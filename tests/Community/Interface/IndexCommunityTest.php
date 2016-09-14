<?php
include_once('CommunityApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexCommunityApiTest extends CommunityApi
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES 

    /** @test */
    public function default_community_filter(){
        $this->get($this->route);
        
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
