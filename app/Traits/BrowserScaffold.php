<?php

namespace App\Traits;

use \Exception;
use \Laravel\Dusk\Browser as Dusk;
use \Laravel\Dusk\Chrome\SupportsChrome;
use \Laravel\Dusk\Concerns\ProvidesBrowser;
use \Facebook\WebDriver\Chrome\ChromeOptions;
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\Remote\DesiredCapabilities;


trait BrowserScaffold
{
    use ProvidesBrowser,
        SupportsChrome;

    public static function getName(){
        return 'BrowserScaffold';
    }

    function __construct()
    {
        static::startChromeDriver();

        Dusk::$baseUrl = config('app.url');

        Dusk::$storeScreenshotsAt = base_path('tests/BrowserScaffold/screenshots');

        Dusk::$storeConsoleLogAt = base_path('tests/BrowserScaffold/console');

        Dusk::$userResolver = function () {
            return $this->user();
        };
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        /*
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        ));
        */

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()
        );
    }

    /**
     * Return the default user to authenticate.
     *
     * @return \App\User|int|null
     * @throws \Exception
     */
    protected function user()
    {
        throw new Exception("User resolver has not been set.");
    }

}