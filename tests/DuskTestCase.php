<?php

namespace Tests;

use Config;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use  Laravel\Dusk\TestCase as BaseTestCase;
use Tests\Browser\Browser;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication, PolishedTest;

    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['betaMessage.on' => 0]);

        Config::set('mail.driver', 'log');
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     *
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome(), 5000, 10000
        );
    }

    /**
     * Create a new Browser instance using the extended browser rather than laravel.
     *
     * @param \Facebook\WebDriver\Remote\RemoteWebDriver $driver
     *
     * @return \Tests\Browser\Browser
     */
    protected function newBrowser($driver)
    {
        return new Browser($driver);
    }

    /**
     * Global teardown does a pretty good job of deleting things setup as
     * class variables during execution.
     *
     * @return void
     */
    public function tearDown()
    {
        foreach (get_object_vars($this) as $variable) {
            if (method_exists($variable, 'delete')) {
                $variable->delete();
            }
        }

        parent::tearDown();
    }
}
