<?php

namespace Acme\Bundle\TestsBundle\Tests\Selenium;

use Acme\Bundle\TestsBundle\Pages\BAP\Login;

class NavigationTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected $coverageScriptUrl = PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL_COVERAGE;

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
     * Test for User tab navigation
     */
    public function testUserTab()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit()
            ->openNavigation()
            ->tab('Users')
            ->menu('Users')
            ->assertElementPresent("//table[@class='grid table-hover table table-bordered table-condensed']/tbody");

        $login->openNavigation()
            ->tab('Users')
            ->menu('Roles')
            ->assertElementPresent("//table[@class='grid table-hover table table-bordered table-condensed']/tbody");

        $login->openNavigation()
            ->tab('Users')
            ->menu('Groups')
            ->assertElementPresent("//table[@class='grid table-hover table table-bordered table-condensed']/tbody");
    }

    /**
     * Test Pinbar History
     *
     * @depends testUserTab
     */
    public function testPinbarHistory()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit();
        //Open History pinbar dropdown
        $this->byXPath("//div[@class='pin-menus dropdown dropdown-close-prevent']/span")->click();
        $this->waitForAjax();
        $this->isElementPresent("//div[@class='tabbable tabs-left']");
        $this->byXPath("//div[@class='tabbable tabs-left']//a[contains(., 'History')]")->click();
        $this->waitForAjax();
        //Check that user, group and roles pages added
        $this->assertTrue(
            $this->isElementPresent("//div[@id='history-content'][//a[contains(., 'Users')]][//a[contains(., 'Roles')]][//a[contains(., 'Groups')]]"),
            'Not found in History tab'
        );
    }

    /**
     * Test Pinbar Most Viewed
     *
     * @depends testUserTab
     */
    public function testPinbarMostViewed()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit();
        //Open Most viewed pinbar dropdown
        $this->byXPath("//div[@class='pin-menus dropdown dropdown-close-prevent']/span")->click();
        $this->waitForAjax();
        $this->isElementPresent("//div[@class='tabbable tabs-left']");
        $this->byXPath("//div[@class='tabbable tabs-left']//a[contains(., 'Most Viewed')]")->click();
        $this->waitForAjax();
        //Check that user, group and roles pages added
        $this->assertTrue(
            $this->isElementPresent("//div[@id='most-viewed-content'][//a[contains(., 'Users')]][//a[contains(., 'Roles')]][//a[contains(., 'Groups')]]"),
            'Not found in Most Viewed section'
        );
    }

    /**
     * Test Pinbar Most Viewed
     *
     */
    public function testPinbarFavorites()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit()
            ->openGroups();
        //Add Groups page to favorites
        $this->byXPath("//button[@class='btn favorite-button']")->click();
        //Open pinbar dropdown Favorites
        $this->byXPath("//div[@class='pin-menus dropdown dropdown-close-prevent']/span")->click();
        $this->waitForAjax();
        $this->isElementPresent("//div[@class='tabbable tabs-left']");
        $this->byXPath("//div[@class='tabbable tabs-left']//a[contains(., 'Favorites')]")->click();
        $this->waitForAjax();
        //Check that page is added to favorites
        $this->isElementPresent("//div[@id='favorites-content' and @class='tab-pane active']");
        $this->waitForAjax();
        $this->assertTrue(
            $this->isElementPresent("//div[@id='favorites-content'][//span[contains(., 'Groups overview - User Management')]]"),
            'Not found in favorites section'
        );
        //Remove Groups page from favorites
        $this->byXPath("//div[@id='favorites-content'][//span[contains(., 'Groups overview - User Management')]]//button[@class='close']")->click();
        $this->waitForAjax();
        //Check that page is deleted from favorites
        $this->assertFalse(
            $this->isElementPresent("//div[@id='favorites-content'][//span[contains(., 'Groups overview - User Management')]]"),
            'Not found in favorites section'
        );
    }

    public function testTabs()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit()
            ->openUsers();
        //Minimize page to pinbar tabs
        $this->byXPath("//div[@class='top-action-box']//button[@class='btn minimize-button']")->click();
        $this->waitForAjax();
        $this->assertTrue(
            $this->isElementPresent("//div[@class='list-bar']//a[contains(., 'Users overview - User Management')]"),
            'Element does not minimised to pinbar tab'
        );
    }

    public function testSearchTab()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit();
        //Click Search tab link
        $this->byXPath("//ul[@class='nav nav-tabs']//a[contains(., 'Search')]")->click();
        $this->waitForAjax();
        //Check that Advanced search
        $this->assertTrue(
            $this->isElementPresent("//*[@id='demo_search_tab']//a[contains(., 'Advaced search')]"),
            'Advanced search tab does not available'
        );
        $this->byXPath("//*[@id='demo_search_tab']//a[contains(., 'Advaced search')]")->click();
        $this->waitForAjax();
        $this->assertTrue(
            $this->isElementPresent("//div[@class='container-fluid']//div[@class='search_stats alert alert-info']"),
            'Element does not presen on page'
        );
    }

    public function testSimpleSearch()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit();
        $this->assertTrue(
            $this->isElementPresent("//div[@id='search-div']//input[@id='search-bar-search']"),
            'Simple search does not available'
        );
    }
}
