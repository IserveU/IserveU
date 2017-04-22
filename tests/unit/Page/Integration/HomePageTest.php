<?php


class HomePageTest extends BrowserKitTestCase
{
    /** @test */
    public function can_visit_home_page()
    {
        $this->get('/')->assertResponseStatus(200); //Redirects to login
    }
}
