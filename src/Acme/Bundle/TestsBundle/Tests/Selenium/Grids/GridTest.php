<?php
namespace Acme\Bundle\TestsBundle\Tests\Selenium;

use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class GridTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    const TIME_OUT  = 1000;
    const MAX_AJAX_EXECUTION_TIME = 15000;

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

    protected function openUserInfoPage($username)
    {
        $this->byXPath("//*[@class='grid table-hover table table-bordered table-condensed']/tbody/tr/td[text() = \"$username\"]")->click();
        $this->assertEquals('Last_'.$username.', First_'.$username.' - Profile - User Management', $this->title());
    }

    protected function searchFilterByUsername($username)
    {
        $this->byXPath("//*[@id='usersDatagridFilters']/div/div/button[contains(., 'Username')]")->click();
        $this->isElementPresent("//*[@class='btn-group filter-item oro-drop open-filter']/div/div/");
        $this->byXPath("//*[@class='btn-group filter-item oro-drop open-filter']/div/div/div/input")->value($username);
        $this->byXPath("//div[@class='btn-group filter-item oro-drop open-filter']//button[contains(., 'Update')]")->click();
        $this->waitForAjax();
    }

    public function testSelectPage()
    {
        $this->url('user');
        $this->waitPageToLoad();
        $this->login($this);
        $this->waitForAjax();
        //open add new usr page
        $userData = $this->getRandomUser();
        $this->assertTrue($this->userExists($userData));
        $this->selectPage(2);
        $this->assertFalse($this->userExists($userData));
        $this->selectPage(1);
        $this->assertTrue($this->userExists($userData));
        $res = $this->getPagesCount();
        $res = $this->getRecordsCount();
    }

    public function testNextPage()
    {
        $this->url('user');
        $this->waitPageToLoad();
        $this->login($this);
        $this->waitForAjax();
        //open add new usr page
        $userData = $this->getRandomUser();
        $this->assertTrue($this->userExists($userData));
        $this->nextPage();
        $this->assertFalse($this->userExists($userData));
        $this->previousPage();
        $this->assertTrue($this->userExists($userData));
    }

    public function testPrevPage()
    {
        $this->url('user');
        $this->waitPageToLoad();
        $this->login($this);
        $this->waitForAjax();
        //open add new usr page
        $userData = $this->getRandomUser();
        $this->assertTrue($this->userExists($userData));
        $this->nextPage();
        $this->assertFalse($this->userExists($userData));
        $this->previousPage();
        $this->assertTrue($this->userExists($userData));
    }

    /**
     * @dataProvider filterData
     */
    public function testFilterBy($filterName, $condition)
    {
        $this->url('user');
        $this->waitPageToLoad();
        $this->login($this);
        $this->waitForAjax();
        //open add new usr page
        $userData = $this->getRandomUser();
        $this->assertTrue($this->userExists($userData));
        $this->selectFilter($filterName, $userData[strtoupper($filterName)], $condition);
        $this->assertTrue($this->userExists($userData));
        $this->assertCount(1, $this->getRecordsOnPage());
        $this->clearFilter($filterName);
    }

    /**
     * @return array
     */
    public function filterData()
    {
        return array(
            'ID' => array('ID', '='),
            'Username' => array('Username', 'is equal to'),
            'Email' => array('Email', 'contains'),
            'First name' => array('First name', 'is equal to'),
            //'Birthday' => array('Birthday', '')
        );
    }

    protected function getRandomUser($pageSize = 25)
    {
        $userId = rand(0, $pageSize-1);
        $users = $this->getRecordsOnPage();
        $user = $users[$userId]->elements($this->using('xpath')->value("td"));
        $columns = $this->elements($this->using('xpath')->value("//table/thead/tr/th"));
        $userData = array();
        for ($i=0; $i< count($columns); $i++) {
            $userData[$columns[$i]->text()] = $user[$i]->text();
        }
        return $userData;
    }

    protected function selectPage($page = 1)
    {
        $paginator = $this->byXPath("//div[contains(@class,'pagination')]/ul//input");
        //set focus
        $paginator->click();
        //todo: it is a workaround for clear method
        $this->keysSpecial('backspace,backspace,backspace');
        $paginator->value($page);
        $this->keysSpecial('enter');
        $this->waitForAjax();
    }

    protected function nextPage()
    {
        $this->byXPath("//div[contains(@class,'pagination')]//a[contains(.,'Next')]")->click();
        $this->waitForAjax();
    }

    protected function previousPage()
    {
        $this->byXPath("//div[contains(@class,'pagination')]//a[contains(.,'Prev')]")->click();
        $this->waitForAjax();
    }

    protected function getCurrentPage()
    {
        return $this->byXPath("//div[contains(@class,'pagination')]/ul//input")->value();
    }

    protected function getRecordsCount()
    {
        $pager = $this->byXPath("//div[contains(@class,'pagination')]//label[@class='dib'][2]")->text();
        preg_match('/of\s+(\d+)\s+\|\s+(\d+)\s+records/i', $pager, $result);
        return $result[2];
    }

    protected function getRecordsOnPage()
    {
        $records = $this->elements($this->using('xpath')->value("//table/tbody/tr"));
        return $records;
    }

    protected function getPagesCount()
    {
        $pager = $this->byXPath("//div[contains(@class,'pagination')]//label[@class='dib'][2]")->text();
        preg_match('/of\s+(\d+)\s+\|\s+(\d+)\s+records/i', $pager, $result);
        return $result[1];
    }

    protected function userExists($userData)
    {
        $xpath = '';
        foreach ($userData as $userField) {
            if ($xpath != '') {
                $xpath .= " and ";
            }
            $xpath .=  "td[contains(.,'{$userField}')]";
        }
        $xpath = "//table/tbody/tr[{$xpath}]";
        return $this->isElementPresent($xpath);
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

    protected function removeFilter($filterName)
    {
        $this->byXPath(
            "//div[contains(@class, 'filter-box')]/div[contains(@class, 'filter-item')]"
            . "[button[contains(.,'{$filterName}')]]/a[contains(., 'Close')]"
        )->click();
        $this->waitForAjax();
    }

    protected function addFilter($filterName)
    {
        $addFilter = $this->byXPath("//div[contains(@class, 'filter-box')]/button[contains(.,'Add filter')]");
        //expand filter list
        $addFilter->click();
        $filter = $this->byXPath("//input[@title='{$filterName}'][@name='multiselect_add-filter-select']");
        if (!$filter->selected()) {
            $filter->click();
        }
        $this->waitForAjax();
        //hide filter list
        $addFilter->click();
    }

    protected function selectFilter($filterName, $value = '', $condition = '')
    {
        $this->byXPath(
            "//div[contains(@class, 'filter-box')]/div[contains(@class, 'filter-item')]"
            . "/button[contains(.,'{$filterName}')]"
        )->click();

        $criteria = $this->byXPath(
            "//div[contains(@class, 'filter-box')]/div[contains(@class, 'filter-item')]"
            . "[button[contains(.,'{$filterName}')]]//div[contains(@class, 'filter-criteria')]"
        );
        $input = $criteria->element($this->using('xpath')->value("div/div/input[@name='value']"));

        $input->clear();
        $input->value($value);

        //select criteria
        if ($condition != '') {
            $criteria->element($this->using('xpath')->value("//div[label[text()='{$condition}']]/input"))->click();
        }
        $criteria->element($this->using('xpath')->value("//button[contains(@class, 'filter-update')]"))->click();
        $this->waitForAjax();
    }

    protected function clearInput($element)
    {
        $element->value('');
        $tx = $element->value();
        while ($tx!="") {
            $this->keysSpecial('backspace');
            $tx = $element->value();
        }
    }

    protected function clearFilter($filterName)
    {
        $this->selectFilter($filterName);
    }
}
