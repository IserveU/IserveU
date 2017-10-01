<?php

namespace Tests;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Tests\DuskTools\TestCase as BaseTestCase;
use Config;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication, PolishedTest;


    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['betaMessage.on'=>0]);

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
}
