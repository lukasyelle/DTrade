<?php

namespace App\Jobs\Robinhood;

use App\Events\PortfoliosUpdated;
use App\Jobs\BrowserJob;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class RefreshPortfolioJob extends BrowserJob
{

    /**
     * This method is to be overwritten in each Job in order to provide the correct
     * entry point in the construction of a job to add the tasks it will execute before
     * adding the jobs tags.
     *
     * @throws \Exception
     */
    public function setup()
    {
        $this->addTasks([
            new Tasks\LoginTask(),
            // @TODO Add tasks that actually grab the stocks in your portfolio so the value may be updated
            // Idea: Snag all links under the first section in [data-testid='InstrumentPreviewList']
            // With a brief test in jQuery, the selector "[data-testid='InstrumentPreviewList'] section" may work.
            // If not, this should "[data-testid='InstrumentPreviewList'] section:first-of-type"
            // This will change if they remove that testid.
            new Tasks\LogoutTask()
        ]);
    }

    public function tearDown()
    {
        \Log::debug($this->user->portfolios());
        event(new PortfoliosUpdated($this->user->portfolios()));
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
            '--no-sandbox'
        ]);


        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        ));
    }

}
