<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class AuthenticationPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/#/login';
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
        parent::assert($browser);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@logo'         => 'img.logo',
            '@message'      => 'div.md-input-messages-animation .md-caption',
            '@firstName'    => 'register-form input[name=firstname]',
            '@lastName'     => 'register-form input[name=lastname]',
            '@newEmail'     => 'register-form input[name=newemail]',
            '@confirmEmail' => 'register-form input[name=confirmemail]',
            '@newPassword'  => 'register-form input[name=newpassword]',
            '@community'    => 'register-form md-select.select_input__communities',
            '@submitCreate' => 'register-form button[type=submit]',
        ];
    }
}
