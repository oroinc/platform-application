<?php

namespace Acme\Bundle\TestsBundle\Tests\Selenium;

use Acme\Bundle\TestsBundle\Test\ToolsAPI;

class RolesTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected $newRole = array('LABEL' => 'NEW_LABEL_', 'ROLE_NAME' => 'NEW_ROLE_');

    protected $defaultRoles = array(
        'header' => array('ID' => 'ID', 'ROLE' => 'ROLE', 'LABEL' => 'LABEL', '' => 'ACTION'),
        '1' => array('1' => '1', 'ROLE_MANAGER' => 'ROLE_MANAGER', 'Manager' => 'Manager', '...' => 'ACTION'),
        '2' => array('2' => '2', 'ROLE_ADMIN' => 'ROLE_ADMIN', 'Administrator' => 'Administrator', '...' => 'ACTION'),
        '3' => array('3' => '3', 'IS_AUTHENTICATED_ANONYMOUSLY' => 'IS_AUTHENTICATED_ANONYMOUSLY', 'Anonymous' => 'Anonymous', '...' => 'ACTION'),
        '4' => array('4' => '4', 'ROLE_USER' => 'ROLE_USER', 'User' => 'User', '...' => 'ACTION'),
        '5' => array('5' => '5', 'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN', 'Super admin' => 'Super admin', '...' => 'ACTION')
    );

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

    public function testRolesGrid()
    {
        $this->url('user/role');
        $this->waitPageToLoad();
        $this->login(&$this);

        $this->assertContains('Roles overview - User management', $this->title());
    }

    public function testRolesGridDefaultContent()
    {
        $this->url('user/role');
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

    public function testRolesAdd()
    {
        $this->url('user/role');
        $this->waitPageToLoad();
        $this->login($this);
        $this->byXPath("//a[contains(., 'Add new')]")->click();
        $this->waitPageToLoad();
        $randomPrefix = ToolsAPI::randomGen(5);
        $this->byId('oro_user_role_form_role')->value($this->newRole['ROLE_NAME'] . $randomPrefix);
        $this->byId('oro_user_role_form_label')->value($this->newRole['LABEL'] . $randomPrefix);
        $this->byXPath("//button[contains(., 'Save')]")->click();
        //verify message
        $this->assertInstanceOf(
            'PHPUnit_Extensions_Selenium2TestCase_Element',
            $this->byXPath("//div[contains(@class,'alert') and contains(.,  'Role successfully saved')]")
        );
        //verify new ROLE
        $this->assertInstanceOf(
            'PHPUnit_Extensions_Selenium2TestCase_Element',
            $this->byXPath(
                "//table[contains(@class, 'grid')]//tr/td[text() = '" .
                'ROLE_' . $this->newRole['ROLE_NAME'] . strtoupper($randomPrefix) . "']"
            )
        );

        return $randomPrefix;
    }

    /**
     * @depends testRolesAdd
     * @param $role
     */
    public function testRoleDelete($role)
    {
        $this->url('user/role');
        $this->waitPageToLoad();
        $this->login($this);
        $row = $this->byXPath(
            "//table[contains(@class, 'grid')]//tr[td[text() = '" .
            'ROLE_' . $this->newRole['ROLE_NAME'] . strtoupper($role) . "']]"
        );
        $row->element($this->using('xpath')->value("td[@class = 'action-cell']//a[contains(., '...')]"))->click();
        $row->element($this->using('xpath')->value("td[@class = 'action-cell']//a[contains(., 'Delete')]"))->click();
        $this->byXPath("//div[div[contains(., 'Delete Confirmation')]]//a[text()='OK']")->click();
        $this->waitForAjax();
    }
}
