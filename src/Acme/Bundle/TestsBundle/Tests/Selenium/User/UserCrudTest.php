<?php
namespace Acme\Bundle\TestsBundle\Tests\Selenium;

class UserCrudTest extends \PHPUnit_Extensions_Selenium2TestCase
{
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
        $script = "return 'complete' != document['readyState']";
        do {
            sleep(1);
        } while ($this->execute(array('script' => $script, 'args' => array())));

        $this->timeouts()->implicitWait(self::TIME_OUT);
    }

    protected function waitForAjax($iSec = 1)
    {
        $i = 0;
        do {
            $bRunning = $this->execute(array('script' => 'return jQuery.active != 0', 'args' => array()));
            if ($bRunning) {
                if ($i > self::MAX_AJAX_EXECUTION_TIME) {
                    break;
                }
                $i++;
                sleep($iSec);
            }
        } while ($bRunning);
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
        $this->assertEquals($username.' - profile - Dashboard', $this->title());
    }

    protected function searchFilterByUsername($username)
    {
        $this->byXPath("//*[@id='usersDatagridFilters']/div/div/button[contains(., 'Username')]")->click();
        $this->isElementPresent("//*[@class='btn-group filter-item oro-drop open-filter']/div/div/");
        $this->byXPath("//*[@class='btn-group filter-item oro-drop open-filter']/div/div/div/input")->value($username);
        $this->byXPath("//div[@class='btn-group filter-item oro-drop open-filter']//button[contains(., 'Update')]")->click();
        $this->waitForAjax();
    }
    /**
     * @return string
     */
    public function testCreateUser()
    {
        $this->url('user');
        $this->waitPageToLoad();
        $this->login($this);
        //open add new usr page
        $this->byXPath("//*[@id='main']/div/h1/a[contains(., 'Add new')]")->click();
        $this->waitPageToLoad();
        $this->assertEquals('Add profile - Dashboard', $this->title());
        //fill form
        $username = 'User_'.mt_rand();
        $this->byId('oro_user_profile_form_username')->value($username);
        $this->byId('oro_user_profile_form_plainPassword_first')->value('123123q');
        $this->byId('oro_user_profile_form_plainPassword_second')->value('123123q');
        $this->byId('oro_user_profile_form_firstName')->value('First_'.$username);
        $this->byId('oro_user_profile_form_lastName')->value('Last_'.$username);
        $this->byId('oro_user_profile_form_email')->value($username.'@mail.com');
        $this->byId('oro_user_profile_form_rolesCollection_0')->click();
        $this->byXPath("//*[@class='pull-right']/button[contains(., 'Save')]")->click();
        $this->waitPageToLoad();
        //check that succesful message displayed
        $this->assertEquals('Users overview - Dashboard', $this->title());
        $this->assertTrue(
            $this->isElementPresent("//div[contains(@class,'alert') and contains(., 'User successfully saved')]"),
            'Message that user is created not found'
        );
        return $username;
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

    /**
     * @depends testCreateUser
     * @param $username
     * @return string
     */
    public function testUpdateUser($username)
    {
        $this->url('user');
        $this->waitPageToLoad();
        $this->login($this);
        $this->searchFilterByUsername($username);
        $this->openUserInfoPage($username);
        //open edit user page
        $this->byXPath("//*[@class='btn-group icons-holder']/a[contains(., 'Edit')]")->click();
        $this->waitPageToLoad();
        $this->assertEquals('Edit profile - Dashboard', $this->title());
        //editing user info
        $username = 'Update_'.$username;
        $this->byId('oro_user_profile_form_username')->clear();
        $this->byId('oro_user_profile_form_username')->value($username);
        $this->byXPath("//*[@class='pull-right']/button[contains(., 'Save')]")->click();
        $this->waitPageToLoad();
        //check that success message displayed
        $this->assertEquals($username.' - profile - Dashboard', $this->title());
        $this->assertTrue(
            $this->isElementPresent("//div[contains(@class,'alert') and contains(., 'User successfully saved')]"),
            'Message that user is created not found'
        );

        return $username;
    }

    /**
     * @depends testUpdateUser
     * @param $username
     */
    public function testDeleteUser($username)
    {
        $this->url('user');
        $this->waitPageToLoad();
        $this->login($this);
        $this->searchFilterByUsername($username);
        $this->openUserInfoPage($username);
        //delete user
        $this->byXPath("//*[@class='btn-group icons-holder']/a[contains(., 'Remove')]")->click();
        $this->waitPageToLoad();
        $this->assertEquals('Users overview - Dashboard', $this->title());
        //check that success remove message displayed
        $this->assertTrue(
            $this->isElementPresent("//div[contains(@class,'alert') and contains(., 'User successfully removed')]"),
            'Message that user is removed not found'
        );
        //check that this user can't be found by filter search
        $this->byXPath("//*[@id='usersDatagridFilters']/div/div/button[contains(., 'Username')]")->click();
        $this->isElementPresent("//*[@class='btn-group filter-item oro-drop open-filter']/div/div/");
        $this->byXPath("//*[@class='btn-group filter-item oro-drop open-filter']/div/div/div/input")->value($username);
        $this->byXPath("//div[@class='btn-group filter-item oro-drop open-filter']//button[contains(., 'Update')]")->click();
        $this->assertTrue(
            $this->isElementPresent(
                "//*[@class='no-data']/span[contains(., 'No users were found to match your search.')]",
                'Message that no users found not displayed'
            )
        );
    }
}
