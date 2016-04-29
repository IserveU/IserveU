<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionTest extends TestCase 
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
        $this->user->addUserRoleByName('administrator');

    }


    /** @test */
    public function get_motion_index_of_only_published()
    {
        $index = $this->call('GET', 'api/motion');

        $this->assertResponseOk();

    }


}