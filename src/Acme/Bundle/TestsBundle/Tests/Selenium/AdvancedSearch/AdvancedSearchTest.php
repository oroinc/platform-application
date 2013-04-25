<?php

namespace Acme\Bundle\TestsBundle\Tests\Selenium;

use Acme\Bundle\TestsBundle\Pages\BAP\Login;
use Acme\Bundle\TestsBundle\Pages\BAP\Users;

class AdvancedSearchTest extends \PHPUnit_Extensions_Selenium2TestCase
{
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
            intval('MAX_AJAX_EXECUTION_TIME')
        );

        $this->timeouts()->implicitWait(intval('TIME_OUT'));
    }

    /**
     * Verify element present
     *
     * @param string $locator
     * @return bool
     */
    public function isElementPresent($locator)
    {
        try {
            $this->byXPath($locator);
            return true;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return false;
        }
    }

    /**
     * Tests that checks advanced search
     *
     * @dataProvider columnTitle
     */
    public function testAdvancedSearch($query, $userField)
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit()
            ->openUsers();
        $users = new Users($this);
        $userData = $users->getRandomEntity();
        //Open advanced search page
        $this->byXPath("//ul[@class='nav nav-tabs']//a[contains(., 'Search')]")->click();
        $this->waitForAjax();
        $this->byXPath("//*[@id='demo_search_tab']//a[contains(., 'Advanced search')]")->click();
        $this->waitForAjax();
        //Fill advanced search input field
        $this->byId('query')->value($query . $userData{$userField});
        $this->byId('sendButton')->click();
        $this->waitForAjax();
        //Check that result is not null
        $this->assertFalse(
            $this->isElementPresent(
                "//div[@class='container-fluid']//div[@class='search_stats alert alert-info']/h2[contains(., '$query . $userData{$userField}')]"
            ),
            'Search results does not found'
        );
    }

    /**
     * Data provider for advanced search
     *
     * @return array
     */
    public function columnTitle()
    {
        return array(
            'firstName' => array('where firstName ~ ','FIRST NAME'),
        );
    }
}
