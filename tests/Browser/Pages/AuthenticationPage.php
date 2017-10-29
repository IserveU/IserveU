<?php

namespace Tests\Browser\Pages;

use Tests\DuskTools\Browser;

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
            '@login'          => 'form[name="loginForm"] .login__button button',
            '@create'         => 'form[name="loginForm"] button.create__button',
            '@email'          => 'form[name="loginForm"] input[name=email]',
            '@password'       => 'form[name="loginForm"] input[name=password]',
            '@logo'           => 'img.logo',
            '@message'        => 'div.md-input-messages-animation .md-caption',
            '@forgotPassword' => '[ng-click="login.sendResetPassword()"]',
            '@firstName'      => 'register-form input[name=firstname]',
            '@lastName'       => 'register-form input[name=lastname]',
            '@newEmail'       => 'register-form input[name=newemail]',
            '@confirmEmail'   => 'register-form input[name=confirmemail]',
            '@newPassword'    => 'register-form input[name=newpassword]',
            '@community'      => 'register-form md-select.select_input__communities',
            '@submitCreate'   => 'register-form button[type=submit]',
        ];
    }
}
