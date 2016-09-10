<?php
include_once('CommentApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexCommentApiTest extends TestCase
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES 

    /** @test */
    public function filter_comment_by(){
        
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
