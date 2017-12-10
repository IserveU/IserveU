<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class MotionAdministratePage extends Page
{
    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        parent::assert($browser);
    }

    //md-select.select_input__communities

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
          '@title'      => 'input[name="title"]',
          '@summary'    => 'input[name="summary"]',
          '@motionText' => '.cke_textarea_inline',
          '@department' => 'md-select[name="department"]',
          '@status'     => 'md-select[name="status"]',
          '@save'       => '.create-motion__button button[type="submit"]',
          '@cancel'     => 'create-motion__button button[type="button"]',
        ];
    }
}
