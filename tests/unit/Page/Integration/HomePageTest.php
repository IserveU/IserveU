<?php


class HomePageTest extends BrowserKitTestCase
{
    /** @test */
    public function can_visit_home_page()
    {
        $this->get('/')->assertResponseStatus(200); //Redirects to login
    }

    /** @test */
    public function can_see_required_bootstrapping()
    {
        $this->get('/')
          ->see('<link rel="icon shortcut" type="image/png" href="/api/page/1/file/symbol-png/resize/100">')
          ->see('<user-bar')
          ->see('<md-sidenav')
          ->see('<div id="maincontent"');
    }
}
