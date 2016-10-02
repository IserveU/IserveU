<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\File;

use Carbon\Carbon;

class UploadFileFlowWayTest extends TestCase
{

    //use WithoutMiddleware; Needed for the generation of expections

    use DatabaseTransactions;

    public function setUp(){

        parent::setUp();
        $this->signIn();
        $this->user->addUserRoleByName('administrator');
    }

    /**
     * @test
     */
    public function can_store_file_using_flow_system(){
        $this->markTestSkipped('We will figure this out later');
    }


}
