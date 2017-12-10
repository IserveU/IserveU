<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser as DuskBrowser;

class Browser extends DuskBrowser
{
    /**
     * The base laravel one doesn't really do the trick for SPAs.
     *
     * @param string $guard
     *
     * @return $browser
     */
    public function logout($guard = null)
    {
        $this->driver->executeScript('localStorage.clear();');
        $this->driver->manage()->deleteAllCookies();
        $this->refresh();

        return $this;
    }

    /**
     * Overrides base class
     * Log into the application by setting up local storage with the users variables.
     *
     * @param User $user
     * @param
     *
     * @return $browser
     */
    public function loginAs($user, $guard = null)
    {
        $this->visit('/'); //->pause(500);
        $user = $user->fresh(); // Some details generated after insert, need to fetch copy
        $this->driver->executeScript("localStorage.setItem('api_token', '$user->api_token');");
        $this->driver->executeScript("localStorage.setItem('user', JSON.stringify(".json_encode($user->skipVisibility(['permissions'])->toArray()).'));');
        $this->driver->executeScript("localStorage.setItem('remember_me', 'true');");
        $this->driver->executeScript("localStorage.setItem('agreement_accepted', 'true');");
        $this->refresh();

        return $this;
    }
}
