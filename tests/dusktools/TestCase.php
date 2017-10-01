<?php

namespace Tests\DuskTools;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
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
            'http://localhost:9515', DesiredCapabilities::chrome()
        );
    }

    /**
     * Create a new Browser instance.
     *
     * @param \Facebook\WebDriver\Remote\RemoteWebDriver $driver
     *
     * @return \Laravel\Dusk\Browser
     */
    protected function newBrowser($driver)
    {
        return (new Browser($driver));
                // Done in page object assertion  ->resize(1920,1080);
    }

    /**
     * Global setup.
     */
    public function setUp()
    {
        parent::setUp();
        // Warning:
      //  \PHPUnit_Framework_Error_Warning::$enabled = false;

        // notice, strict:
      //  \PHPUnit_Framework_Error_Notice::$enabled = false;

        //Any global things
    }

    /**
     * Global tear down tries to delete any class variables created.
     * Does a pretty good job of isolating test environments.
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
