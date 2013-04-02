<?php
namespace Acme\Bundle\TestsBundle\Tests\Selenium;

class TestLoginForm extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort(intval(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT));
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM2_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
    }

    public function testHasLoginForm()
    {
        $username = $this->byId('prependedInput');
        $password = $this->byId('prependedInput2');

        //check that username and password is empty field
        $this->assertEquals('', $username->value());
        $this->assertEquals('', $password->value());
    }

    public function testLoginFormSubmitsToAdmin()
    {
        $this->byId('prependedInput')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN);
        $this->byId('prependedInput2')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS);
        $this->clickOnElement('_submit');

        $this->timeouts()->implicitWait(10000);
        $this->assertEquals('Dashboard', $this->title());

        $this->byXPath("//*[@id='top-page']//div/div/div/ul[2]/li[1]/a")->click();
        $this->byXPath("//*[@id='top-page']//div/ul//li/a[contains(.,'Logout')]")->click();
        $this->timeouts()->implicitWait(50000);
        $this->assertEquals('Login - User management', $this->title());
    }

    /**
     * @dataProvider createData
     * @param $login
     * @param $password
     */
    public function testLoginFormNotSubmitsToAdmin($login, $password)
    {
        $this->byId('prependedInput')->value($login);
        $this->byId('prependedInput2')->value($password);
        $this->clickOnElement('_submit');

        $this->timeouts()->implicitWait(10000);
        $actualResult = $this->byXPath("//*[@id='top-page']/div/div/div[contains(.,'Bad credentials')]")->text();

        $this->assertContains('Login - User management', $this->title());
        $this->assertEquals('Bad credentials', $actualResult);
    }

    /**
     * @return array
     */
    public static function createData()
    {
        return array(
            array(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN, '12345'),
            array('12345', PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
        );
    }

    public function testLoginRequiredFiled()
    {
        $usernameAttribute = $this->byId('prependedInput')->attribute('required');
        $passwordAttribute = $this->byId('prependedInput2')->attribute('required');

        //check that username and password is empty field
        $this->assertEquals('true', $usernameAttribute);
        $this->assertEquals('true', $passwordAttribute);
    }

    public function testRememberFunction()
    {
        $this->byId('prependedInput')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN);
        $this->byId('prependedInput2')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS);
        $this->byId('remember_me')->click();

        $this->clickOnElement('_submit');
        $this->timeouts()->implicitWait(10000);
        $this->assertEquals('Dashboard', $this->title());

        $this->url(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->timeouts()->implicitWait(10000);
        $this->assertEquals('Dashboard', $this->title());
    }

    public function testForgotPassword()
    {
        $this->byXPath("//*[@id='top-page']//fieldset//a[contains(.,'Forgot your password?')]")->click();
        $this->timeouts()->implicitWait(10000);

        $this->assertEquals('Password reset request - User management', $this->title());

        $this->byId('prependedInput')->value('123test123');
        $this->byXPath("//button[contains(.,'Request')]")->click();

        $messageActual = $this->byXPath("/*[@id='top-page']//p[contains(.,'The username or email address')]")->text();
        $messageExpect = "The username or email address \"123test123\" does not exist.";
        $this->assertEquals($messageExpect, $messageActual);

        $this->byId('prependedInput')->value('admin@example.com');
        $this->byXPath("//button[contains(.,'Request')]")->click();
        $this->timeouts()->implicitWait(10000);
        $this->assertEquals('Password reset - check Email - Dashboard', $this->title());

        $messageSuccess = $this->byClassName("alert alert-success")->text();
        $this-assertRegExp('/email has been sent to/i', $messageSuccess);
    }
}
