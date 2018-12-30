<?php

namespace App\Traits;

use \Exception;
use \Laravel\Dusk\Browser;
use \Laravel\Dusk\Chrome\SupportsChrome;
use \Laravel\Dusk\Concerns\ProvidesBrowser;
use \Facebook\WebDriver\Chrome\ChromeOptions;
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\Remote\DesiredCapabilities;
use Tests\CreatesApplication;


trait BrowserScaffold
{
    use CreatesApplication,
        ProvidesBrowser,
        SupportsChrome;

    private $basePath;

    function __construct()
    {
        \Log::debug('Browser Config Setup!');

        $this->basePath = base_path('tests/Browser/screenshots');

        $this->createApplication();

        static::startChromeDriver();

        Browser::$baseUrl = config('app.url');

        Browser::$storeScreenshotsAt = base_path('tests/Browser/screenshots');

        Browser::$storeConsoleLogAt = base_path('tests/Browser/console');
    }

    public static function getName(){
        return 'BrowserScaffold';
    }

    /**
     * Capture failure screenshots for each browser.
     *
     * @param  \Illuminate\Support\Collection  $browsers
     * @return void
     */
    protected function captureFailuresFor($browsers)
    {
        $browsers->each(function ($browser, $key) {
            $name = str_replace('\\', '_', get_class($this)).'_'.$this->getName(false);
            $browser->screenshot("$this->basePath/failure-$name-$key");
        });
    }

    /**
     * Store the console output for the given browsers.
     *
     * @param  \Illuminate\Support\Collection  $browsers
     * @return void
     */
    protected function storeConsoleLogsFor($browsers)
    {
        $browsers->each(function ($browser, $key) {
            $name = str_replace('\\', '_', get_class($this)).'_'.$this->getName(false);

            $browser->storeConsoleLog("$this->basePath/$name-$key");
        });
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
     * Determine the application's base URL.
     *
     * @return string
     */
    protected function baseUrl()
    {
        return config('app.url');
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