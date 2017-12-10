<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

class LoginBox extends Component
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return 'form[name=loginForm]';
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
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@login'          => '.login__button button',
            '@create'         => 'button.create__button',
            '@email'          => 'input[name=email]',
            '@password'       => 'input[name=password]',
            '@forgotPassword' => '[ng-click="login.sendResetPassword()"]',
        ];
    }

    public function loginWith($browser, $email, $password)
    {
        $browser->type('@email', $email)
                ->type('@password', $password)
                ->press('@login');
    }

    public function clickCreate($browser)
    {
        $browser->click('@create');
    }
}
