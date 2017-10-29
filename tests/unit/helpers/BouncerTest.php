<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class BouncerTest extends BrowserKitTestCase
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
    public function bouncer_works_when_on()
    {
        $this->setEnv(['app.bouncer' => true]);
        $this->storeContentGetSee(['title' => 'Motion Title', 'summary' => 'Motion summary', 'not_a_thing' => 'Plus vite'], 400, 'not_a_thing');
    }

    /** @test  */
    public function bouncer_off_when_off()
    {
        $this->setEnv(['app.bouncer' => false]);

        $this->post('/api/motion', ['title' => 'Motion Title', 'summary' => 'Motion summary', 'not_a_thing' => 'Plus vite'])
             ->assertResponseStatus(200)
             ->seeInDatabase('motions', ['title' => 'Motion Title']);
    }
}
