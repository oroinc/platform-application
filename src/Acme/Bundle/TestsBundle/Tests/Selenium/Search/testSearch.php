<?php
namespace Acme\Bundle\TestsBundle\Tests\Selenium\Search;

class TestSearchForm extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort(intval(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT));
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM2_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
    }

    public function testEmptySearchForm()
    {
        $this->byId('prependedInput')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN);
        $this->byId('prependedInput2')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS);
        $this->clickOnElement('_submit');

        $this->byId('search-bar-search')->value(' ');
        $this->byXPath("//*[@id='search-div']//div/button[contains(.,'Search')]")->click();

        $this->assertEquals('Search results: - Dashboard', $this->title());

        $searchResult = $this->byXPath("//*[@id='main']/h2[contains(.,'Sorry, there are no results')]")->text();
        $this->assertEquals('Sorry, there are no results for your request', $searchResult);
    }

    public function testSearchSuggestions()
    {
        $this->byId('prependedInput')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN);
        $this->byId('prependedInput2')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS);
        $this->clickOnElement('_submit');

        $this->byId('search-bar-search')->value('zxcv-');
        $this->timeouts()->implicitWait(10000);

        $this->assertTrue($this->isElementPresent("//*[@id='search-dropdown']/ul/li"), 'No search suggestions available');
    }

    public function isElementPresent($locator)
    {
        try {
            $this->byXPath($locator);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
