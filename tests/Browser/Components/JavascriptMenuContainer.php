<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

/**
 * Both Select Boxes and Menus create dropdown boxes out of line with the triggering component.
 */
class JavascriptMenuContainer extends Component
{
    protected $selector;

    /**
     * The real selector in a selectbox is the menu container
     * which everything is inside.
     */
    public function __construct()
    {
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
        $browser->waitFor($this->selector())->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [

        ];
    }

    public function selectOption($browser, $option)
    {
        $browser->pause(1000); // Opening menu time
        $lookup = "//div[not(contains(@style,'display:none')) and contains(@class, 'md-clickable')]//md-option[.//div[contains(text(), '".$option."')]]";

        $option = $browser->driver->findElement(
            WebDriverBy::xpath($lookup)
        );

        $option->click();

        $browser->pause(1000); // Closing menu time
    }

    public function seeOption($browser, $option)
    {
        $browser->assertSeeIn('', $option);
    }
}
