<?php

namespace Tests\DuskTools;

use Laravel\Dusk\ElementResolver as DuskElementResolver;

//Improve element resolutions
class ElementResolver extends DuskElementResolver
{
    public static $attempts = 0;

    /**
     * I don't get why Dusk doesn't do this, but this should be better for users.
     *
     * @param anything $selector whatever needs to be found
     *
     * @return Selector
     */
    public function resolveBetter($selector)
    {
        if (is_object($selector)) {
            return $selector;
        }

        $exception = '';
        $notVisibleOption = null;

        try {
            $element = $this->findOrFail($selector);

            if ($element->isDisplayed()) {
                return $element;
            }
            $notVisibleOption = $element;
        } catch (\Facebook\WebDriver\Exception\InvalidSelectorException $e) {
            $exception .= 'Invalid selector for findOrFail. ';
        } catch (\Facebook\WebDriver\Exception\NoSuchElementException $e) {
            $exception .= 'Not located with findOrFail. ';
        }

        try {
            $element = $this->resolveForButtonPress($selector);

            if ($element->isDisplayed()) {
                return $element;
            }
            $notVisibleOption = $element;
        } catch (\InvalidArgumentException $e) {
            $exception .= 'Not found with Button Press. ';
        }

        try {
            $element = $this->resolveForTyping($selector);
            if ($element->isDisplayed()) {
                return $element;
            }
            $notVisibleOption = $element;
        } catch (\Exception $e) {
            $exception .= 'Not found for typing ';
        }

        //Add other resolvers here
        //...
        // Returns a thing, but it isn't visible
        if ($notVisibleOption) {
            return $notVisibleOption;
        }

        if (static::$attempts > 3) {
            throw new \InvalidArgumentException($exception);
        }
        //Try again
        static::$attempts++;
        $this->resolveBetter($selector);
    }
}
