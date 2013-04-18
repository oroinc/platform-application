<?php

namespace Acme\Bundle\TestsBundle\Tests\Selenium;

class SimpleSearchTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    const TIME_OUT  = 1000;
    const MAX_AJAX_EXECUTION_TIME = 5000;

    protected function setUp()
    {
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort(intval(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT));
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM2_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
    }

    protected function tearDown()
    {
        $this->cookie()->clear();
    }

    public function isElementPresent($locator)
    {
        try {
            $this->byXPath($locator);
            return true;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return false;
        }
    }

    protected function waitPageToLoad()
    {
        $this->waitUntil(
            function ($testCase) {
                $status = $testCase->execute(array('script' => "return 'complete' == document['readyState']", 'args' => array()));
                if ($status) {
                    return true;
                } else {
                    return null;
                }
            },
            self::MAX_AJAX_EXECUTION_TIME
        );

        $this->timeouts()->implicitWait(self::TIME_OUT);
    }

    protected function waitForAjax()
    {
        $this->waitUntil(
            function ($testCase) {
                $status = $testCase->execute(array('script' => 'return jQuery.active == 0', 'args' => array()));
                if ($status) {
                    return true;
                } else {
                    return null;
                }
            },
            self::MAX_AJAX_EXECUTION_TIME
        );

        $this->timeouts()->implicitWait(self::TIME_OUT);
    }

    /**
     * @param $form
     */
    protected function login($form)
    {
        $name = $form->byId('prependedInput');
        $password = $form->byId('prependedInput2');
        $name->clear();
        $name->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN);
        $password->clear();
        $password->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS);
        $form->clickOnElement('_submit');
        $form->waitPageToLoad();
    }

    public function testSearchSuggestions()
    {
        $this->url('user/login');
        $this->waitPageToLoad();
        //log-in
        $this->login($this);
        //fill-in simple search field
        $this->byId('search-bar-search')->value('admin@example.com');
        //checking that search suggestion drop-down available or not
        $this->waitForAjax();
        $this->assertTrue(
            $this->isElementPresent("//*[@id='search-dropdown']/ul/li/a[contains(., 'admin')]"),
            'No search suggestions available'
        );
    }

    public function testSearchResult()
    {
        $this->url('user/login');
        $this->waitPageToLoad();
        $this->login($this);
        $this->byId('search-bar-search')->value('admin');
        $this->byXPath("//*[@id='search-div']//div/button[contains(.,'Search')]")->click();
        $this->waitPageToLoad();

        $searchResult = $this->byXPath("//div[@class='container-fluid']/div/h3/a[contains(., 'admin')]")->text();
        $this->assertEquals('admin', $searchResult);
    }
}
