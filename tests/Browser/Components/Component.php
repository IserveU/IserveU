<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class Component extends BaseComponent
{
    protected $selector;

    /**
     * There are so many of these that you can't assume that there is only
     * on the page.
     *
     * @param string $selector
     */
    public function __construct($selector = null)
    {
        $this->selector = $selector;
    }

    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param Browser $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->waitFor($this->selector());
    }
}
