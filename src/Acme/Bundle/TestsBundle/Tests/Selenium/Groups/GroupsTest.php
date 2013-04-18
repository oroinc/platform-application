<?php

namespace Acme\Bundle\TestsBundle\Tests\Selenium;

use Acme\Bundle\TestsBundle\Test\ToolsAPI;

class GroupsTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected $newGroup = array('NAME' => 'NEW_GROUP_', 'ROLE' => 'Administrator');

    protected $defaultRoles = array(
        'header' => array('ID' => 'ID', 'NAME' => 'NAME', 'ROLES' => 'ROLES', '' => 'ACTION'),
        '2' => array('2' => '2', 'Administrators' => 'Administrators', '' => 'ROLES', '...' => 'ACTION'),
        '1' => array('1' => '1', 'Managers' => 'Managers', '' => 'ROLES', '...' => 'ACTION')
    );

    const TIME_OUT  = 1000;
    const MAX_AJAX_EXECUTION_TIME = 3000;

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

    public function testGroupsGrid()
    {
        $this->url('user/group');
        $this->waitPageToLoad();
        $this->login(&$this);

        $this->assertContains('Groups overview - User Management', $this->title());
    }

    public function testRolesGridDefaultContent()
    {
        $this->url('user/group');
        $this->waitPageToLoad();
        $this->login($this);

        //get grid content
        $records = $this->elements($this->using('xpath')->value("//table[contains(@class, 'grid')]//tr"));
        foreach ($records as $row) {
            $headers = $row->elements($this->using('xpath')->value("th"));
            foreach ($headers as $header) {
                $content = $header->text();
                $this->assertArrayHasKey($content, $this->defaultRoles['header']);
            }
            $columns = $row->elements($this->using('xpath')->value("td"));
            $id = null;
            foreach ($columns as $column) {
                $content = $column->text();
                if (is_null($id)) {
                    $id = $content;
                }
                $this->assertArrayHasKey($content, $this->defaultRoles[$id]);
            }
        }
    }

    public function testGroupAdd()
    {
        $this->url('user/group');
        $this->waitPageToLoad();
        $this->login($this);
        $this->byXPath("//a[contains(., 'Add new')]")->click();
        $this->waitPageToLoad();
        $this->waitForAjax();
        $randomPrefix = ToolsAPI::randomGen(5);
        $this->byId('oro_user_group_form_name')->value($this->newGroup['NAME'] . $randomPrefix);
        $this->select($this->byId('oro_user_group_form_roles'))->selectOptionByLabel($this->newGroup['ROLE']);
        $this->byXPath("//button[contains(., 'Save')]")->click();
        $this->waitForAjax();
        //verify message
        $this->assertInstanceOf(
            'PHPUnit_Extensions_Selenium2TestCase_Element',
            $this->byXPath("//div[contains(@class,'alert') and contains(.,  'Group successfully saved')]")
        );
        //close dialog
        $this->byXPath("//button[@class ='ui-dialog-titlebar-close']")->click();
        $this->waitForAjax();

        //verify new GROUP
        $this->url('user/group');
        $this->waitPageToLoad();

        $this->assertInstanceOf(
            'PHPUnit_Extensions_Selenium2TestCase_Element',
            $this->byXPath(
                "//table[contains(@class, 'grid')]//tr/td[text() = '" .
                $this->newGroup['NAME'] . $randomPrefix . "']"
            )
        );

        return $randomPrefix;
    }

    /**
     * @depends testGroupAdd
     * @param $group
     */
    public function testGroupDelete($group)
    {
        $this->url('user/group');
        $this->waitPageToLoad();
        $this->login($this);
        $row = $this->byXPath(
            "//table[contains(@class, 'grid')]//tr[td[text() = '" .
            $this->newGroup['NAME'] . $group . "']]"
        );
        $row->element($this->using('xpath')->value("td[@class = 'action-cell']//a[contains(., '...')]"))->click();
        $row->element($this->using('xpath')->value("td[@class = 'action-cell']//a[contains(., 'Delete')]"))->click();
        $this->byXPath("//div[div[contains(., 'Delete Confirmation')]]//a[text()='OK']")->click();
        $this->waitForAjax();
    }
}
