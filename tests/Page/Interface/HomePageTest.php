<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomePageTest extends TestCase
{
    /** @test */
    public function can_visit_home_page()
    {
        $this->get("/")->assertResponseStatus(200);
    }
}
