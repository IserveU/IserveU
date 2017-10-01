<?php

namespace Tests\DuskTools;

abstract class Page
{
    protected $redirect;

    public function __construct($redirect = null)
    {
        $this->redirect = $redirect;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        if ($this->redirect) {
            return $this->redirect;
        }

        return '/';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param \Laravel\Dusk\Browser $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        // Page object bootstrap assertion
        $browser->resize(1920, 1080);

    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }

    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [];
    }
}
