<?php


class HomePageTest extends TestCase
{
    /** @test */
    public function can_visit_home_page()
    {
        $this->get('/')->assertResponseStatus(200); //Redirects to login
    }
}
