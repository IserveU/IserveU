<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class BouncerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->class = App\Motion::class;
        $this->signInAsRole('administrator');
    }

    /*****************************************************************
    *
    *                   Basic CRUD functions:
    *
    ******************************************************************/

    /** @test  */
    public function bouncer_works_when_in_debug_mode()
    {
        $this->setEnv(['app.debug'=>true]);
        $this->storeContentGetSee(['title'=>'Motion Title','summary'=>'Motion summary','not_a_thing'=>'Plus vite'],400,'not_a_thing');

    }

    /** @test  */
    public function bouncer_off_when_in_regular_mode()
    {
        $this->setEnv(['app.debug'=>false]);

        $this->post('/api/motion',['title'=>'Motion Title','summary'=>'Motion summary','not_a_thing'=>'Plus vite'])
             ->assertResponseStatus(200)
             ->seeInDatabase('motions',['title'=>'Motion Title']);


    }
}
