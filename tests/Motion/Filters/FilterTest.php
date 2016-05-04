<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FilterTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
        $this->user->addUserRoleByName('administrator');
    }

    /** @test */
    public function submit_non_status_array()
    {

        //Default with no filters
        filterCheck(
                $this,
                [],
                [],
                ['status'=>1]
        );
        $this->assertResponseStatus(400);

    }
    
    /** @test */
    public function get_motion_index_of_only_published()
    {
        $index = $this->call('GET', 'api/motion',['status'=>[2]]);

        $this->assertResponseOk();

    }

}
