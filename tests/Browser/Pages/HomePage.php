<?php

namespace Tests\Browser\Pages;

use Tests\DuskTools\Browser;

class HomePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/#/home';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->resize(1280, 800);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
              '@homeIntroduction'  => 'home-introduction',
              '@topMotions'        => 'top-motions',
              '@topComments'       => 'top-comments',
          '@yourVotes'             => 'my-votes',
          '@yourComments'          => 'my-comments',
        ];
    }
}
