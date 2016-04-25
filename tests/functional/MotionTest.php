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

      //  factory(App\Motion::class, 'published', 20)->create();
     //   factory(App\Motion::class, 'draft', 20)->create();
    }


    /** @test */
    public function get_motion_index_of_only_published()
    {
        $index = $this->call('GET', 'api/motion', ['token' => $this->token]);

        $this->assertResponseOk();

    }


}