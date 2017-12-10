<?php

namespace Tests\Browser\Pages;

use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use PHPUnit\Framework\Assert as PHPUnit;

abstract class Page extends BasePage
{
    protected $redirect;

    public function __construct($redirect = null)
    {
        $this->redirect = $redirect;
    }

    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            '@termsAndConditions' => 'md-dialog.terms-and-conditions',
            '@sidebarLinks'       => 'sidebar md-list-item',
            '@fab'                => 'md-fab-trigger',
            '@cog'                => '#setting_cog',
        ];
    }

    //div[contains(@class,'panelMessage ')]//span[@class='amountCharged']

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
     * Wait for the given location to contain.
     *
     * @param string $path
     * @param int    $seconds
     *
     * @return $this
     */
    public function waitForLocationContains($browser, $path, $seconds = 5)
    {
        return $browser->waitUntil("window.location.href.indexOf('{$path}') > -1", $seconds);
    }

    /**
     * Asserts that an element attribute is equal to a value.
     *
     * @param [type] $elementSelector
     * @param [type] $attribute
     * @param [type] $assertIs
     *
     * @return void
     */
    public function assertElementAttributeIs($browser, $elementSelector, $attribute, $assertIs)
    {
        $realValue = $browser->attribute($elementSelector, $attribute);
        PHPUnit::assertTrue(
          Str::contains($realValue, $assertIs),
          "Attribute [{$attribute}] on [{$elementSelector}] does not equal [{$assertIs}] it actually equals [{$realValue}]."
      );

        return $browser;
    }

    /**
     * Gets all the elements that match a given selector.
     *
     * @param [type] $browser
     * @param [type] $selector
     *
     * @return void
     */
    public function getAll($browser, $selector)
    {
        $elements = [];

        return $browser->resolver->all($selector);
    }
}
