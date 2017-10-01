<?php

namespace Tests\DuskTools;

use Closure;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser as DuskBrowser;
use PHPUnit\Framework\Assert as PHPUnit;

class Browser extends DuskBrowser
{
    /**
     * Create a browser instance.
     *
     * @param \Facebook\WebDriver\Remote\RemoteWebDriver $driver
     * @param ElementResolver                            $resolver
     *
     * @return void
     */
    public function __construct($driver, $resolver = null)
    {
        $this->driver = $driver;

        $this->resolver = $resolver ?: new ElementResolver($driver);
    }

    /**
     * Resolve the element for a given button by text.
     */
    public function findTagContainingText($tag, $text)
    {
        // I can't figure out why this doesn't find anything in md selects
        foreach ($this->getAll($tag) as $aTag) {
            if (!$aTag->isDisplayed()) {
                continue;
            }

            if (!str_contains($aTag->getText(), $text)) {
                continue;
            }

            //Visible tag containing text
            return $aTag;
        }

        $parts = explode('.', $tag);

        if (count($parts) > 1) {
            return $this->driver->findElement(
          WebDriverBy::xpath("//div[not(contains(@style,'display:none'))]//".$parts[0]."[contains(@class, '".$parts[1]."') and contains(text(), '".$text."')]")
      );
        }

        return $this->driver->findElement(
        WebDriverBy::xpath("//div[not(contains(@style,'display:none'))]//".$tag."[contains(text(), '".$text."')]")
    );
    }

    /**
     * Javascript select list selector.
     *
     * @param string $selectTrigger  The trigger for the list
     * @param int    $value          The value to look for in the list
     * @param string $optionSelector The type of tag used for options
     * @param string $listSelector   The box that the select shows up in
     *
     * @return Browser Dusk browser if the test doesn't fail
     */
    public function jsSelectList($selectTrigger = 'select', $value = 1, $optionSelector = 'md-option', $listSelector = '.md-clickable md-select-menu')
    {

        // This could/should be rewritten to find if the list has a parent with scroll bars, and to scroll up
        // At the moment it just goes to the bottom and then comes back up
        $this->scrollTo(
            0, 3000, 'maincontent'
        );

        $this->pause(250)
             ->clickBetter($selectTrigger)
             ->waitFor($listSelector);

        $list = $this->resolver->resolveBetter($listSelector);
        $element = $this->findTagContainingText($optionSelector, $value);
        $element->getLocationOnScreenOnceScrolledIntoView();

        $this->clickBetter($element)
          ->pause(500);

        return $this;
    }

    public function scrollTo($x, $y, $elementId = null)
    {
        if ($elementId) {
            $this->driver->executeScript("document.getElementById('".$elementId."').scrollTop = $y;");
        } else {
            $this->driver->executeScript("window.scrollTo('.$x.', '.$y.');");
        }

        return $this;
    }

    /**
     * Drop in replacement for click to make it more forgiving.
     *
     * @param String/Element [type] $element [description]
     *
     * @return [type] [description]
     */
    public function clickBetter($element)
    {
        if (!is_object($element)) {
            $element = $this->resolver->resolveBetter($element);
        }

        $this->waitFor($element);

        $element->click();

        return $this;
    }

    /**
     * Drop in replacement for press to make it more forgiving.
     *
     * @param String/Element [type] $element [description]
     *
     * @return [type] [description]
     */
    public function pressBetter($selector)
    {
        $element = $this->resolver->resolveForButtonPress($selector);

        $this->waitFor($element);

        $element->click();

        return $this;
    }

    /**
     * Drop in replacement for type to make it more forgiving.
     *
     * @param string $field an selector (not just the name)
     * @param string $value
     *
     * @return $this
     */
    public function typeBetter($field, $value)
    {
        $this->waitFor($field);

        $this->resolver->resolveBetter($field)
                    ->clear()
                    ->sendKeys($value);

        return $this;
    }

    /**
     * Drop in replacement for see in to make it more forgiving.
     *
     * @param string $selector
     * @param int    $seconds
     *
     * @return $this
     */
    public function assertSeeInBetter($selector, $text)
    {
        return $this->waitFor($selector)
                  ->assertSeeIn($selector, $text);
    }

    /**
     * Overrides base class
     * Wait for the given selector to be visible but also accepts something that is
     * a selector already.
     *
     * @param string|object $selector
     * @param int           $seconds
     *
     * @return $this
     */
    public function waitFor($selector, $seconds = 10)
    {
        return $this->waitUsing($seconds, 100, function () use ($selector) {
            if (is_object($selector)) {
                return $selector->isDisplayed();
            }

            return $this->resolver->resolveBetter($selector)->isDisplayed();
        }, "Waited {$seconds} seconds for selector");
    }

    /**
     * While waiting for the visibility of something perform an action.
     *
     * @param string $selector
     * @param int    $seconds  The time to spend doing this
     *
     * @return $this
     */
    public function whileWaitingFor($selector, $seconds, Closure $callback)
    {
        return $this->waitUsing($seconds, 10, function () use ($selector, $callback) {
            $element = $this->resolver->resolveBetter($selector);
            if ($element && $element->isDisplayed()) {
                return true;
            }

            $callback();
        }, "Waited {$seconds} seconds for selector");
    }

    /**
     * If a selector exists then perform the callback.
     *
     * @param string  $selector
     * @param closure $callback The function to execute in the case that something exists
     *
     * @return $this
     */
    public function ifExistsThen($selector, Closure $callback)
    {
        try {
            $element = $this->resolver->resolveBetter($selector);
        } catch (\InvalidArgumentException $e) { //Not found
            return $this;
        }

        $callback($element);

        return $this;
    }

    /**
     * Extended over base class
     * Accepts an array if you are looking for muliple things on the
     * page.
     *
     * @param array $text
     * @param int   $seconds
     *
     * @return $this
     */
    public function waitForText($text, $seconds = 5)
    {
        if (is_array($text)) {
            $stringIfError = implode($text, ' |OR| ');
        } else {
            $stringIfError = $text;
            $text = [$text];
        }

        return $this->waitUsing($seconds, 100, function () use ($text) {
            foreach ($text as $line) {
                //Blank tag just looks inside the body
                if (Str::contains($this->resolver->findOrFail('')->getText(), $line)) {
                    return true;
                }
            }

            return false;
        }, "Waited {$seconds} seconds for the text {$stringIfError} to show");
    }

    /**
     * Wait for the given location to contain.
     *
     * @param string $path
     * @param int    $seconds
     *
     * @return $this
     */
    public function waitForLocationContains($path, $seconds = 5)
    {
        return $this->waitUntil("window.location.href.indexOf('{$path}') > -1", $seconds);
    }

    public function assertElementAttributeIs($elementSelector, $attribute, $assertIs)
    {
        $realValue = $this->attribute($elementSelector, $attribute);
        PHPUnit::assertTrue(
          Str::contains($realValue, $assertIs),
          "Attribute [{$attribute}] on [{$elementSelector}] does not equal [{$assertIs}] it actually equals [{$realValue}]."
      );

        return $this;
    }

    /**
     * The base laravel one doesn't really do the trick for SPAs.
     *
     * @param string $guard
     *
     * @return $this
     */
    public function logout($guard = null)
    {
        $response = parent::logout();
        $this->driver->executeScript('localStorage.clear();');
        $this->driver->manage()->deleteAllCookies();

        return $response;
    }

    /**
     * Overrides base class
     * Log into the application by setting up local storage with the users variables.
     *
     * @param User $user
     * @param
     *
     * @return $this
     */
    public function loginAs($user, $guard = null)
    {
        if (is_object($user) && !empty($user->api_token)) {
            $this->visit('/')->pause(500);
            $user = $user->fresh(); // Some details generated after insert, need to fetch copy
            $this->driver->executeScript("localStorage.setItem('api_token', '$user->api_token');");
            $this->driver->executeScript("localStorage.setItem('user', JSON.stringify(".json_encode($user->skipVisibility(['permissions'])->toArray()).'));');
            $this->driver->executeScript("localStorage.setItem('remember_me', 'true');");
            $this->driver->executeScript("localStorage.setItem('agreement_accepted', 'true');");

            return $this;
        }

        return parent::loginAs($userId, $guard);
    }

    /**
     * Pause the application so you can click around.
     *
     * @return $this
     */
    public function debug()
    {
        return $this->pause(90000);
    }

    public function getAll($selector)
    {
        $elements = [];

        return $this->resolver->all($selector);
    }
}
