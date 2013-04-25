<?php

namespace Acme\Bundle\TestsBundle\Pages;

use PHPUnit_Framework_Assert;

class Page
{
    /** Default timeouts */
    const TIME_OUT  = 1000;
    const MAX_EXECUTION_TIME = 60000;

    protected $redirectUrl = null;

    /** @var \PHPUnit_Extensions_Selenium2TestCase */
    protected $test;

    /**
     * @param $testCase
     * @param bool $redirect
     */
    public function __construct($testCase, $redirect = true)
    {
        $this->test = $testCase;
        if (!is_null($this->redirectUrl) && $redirect) {
            $this->test->url($this->redirectUrl);
            $this->waitPageToLoad();
            $this->waitForAjax();
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/open(.*)/i', "{$name}", $result) > 0) {
            $class = __NAMESPACE__ . '\\BAP\\' . $result[1];
            return new $class($this);
        }

        if (method_exists($this, $name)) {
            $result = call_user_func_array(array($this, $name), $arguments);
        } else {
            $result = call_user_func_array(array($this->test, $name), $arguments);
        }
        return $result;
    }

    /**
     * Wait PAGE load
     */
    public function waitPageToLoad()
    {
        $this->test->waitUntil(
            function ($testCase) {
                $status = $testCase->execute(array('script' => "return 'complete' == document['readyState']", 'args' => array()));
                if ($status) {
                    return true;
                } else {
                    return null;
                }
            },
            self::MAX_EXECUTION_TIME
        );

        $this->timeouts()->implicitWait(self::TIME_OUT);
    }

    /**
     * Wait AJAX request
     */
    public function waitForAjax()
    {
        $this->test->waitUntil(
            function ($testCase) {
                $status = $testCase->execute(array('script' => 'return jQuery.active == 0', 'args' => array()));
                if ($status) {
                    return true;
                } else {
                    return null;
                }
            },
            self::MAX_EXECUTION_TIME
        );

        $this->timeouts()->implicitWait(self::TIME_OUT);
    }

    public function refresh()
    {
        if (!is_null($this->redirectUrl)) {
            $this->test->url($this->redirectUrl);
            $this->waitPageToLoad();
            $this->waitForAjax();
        }

        return $this;
    }

    /**
     * Verify element present
     *
     * @param string $locator
     * @param string $strategy
     * @return bool
     */
    public function isElementPresent($locator, $strategy = 'xpath')
    {
        try {
            $this->element($this->using($strategy)->value($locator));
            return true;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return false;
        }
    }

    /**
     * @param $title
     * @param string $message
     * @return $this
     */
    public function assertTitle($title, $message = '')
    {
        PHPUnit_Framework_Assert::assertEquals(
            $title,
            $this->test->title(),
            $message
        );
        return $this;
    }

    /**
     * @param $messageText
     * @param string $message
     * @return $this
     */
    public function assertMessage($messageText, $message = '')
    {
        PHPUnit_Framework_Assert::assertTrue(
            $this->isElementPresent("//div[contains(@class,'alert') and contains(., '{$messageText}')]"),
            $message
        );
        return $this;
    }

    /**
     * @param $xpath
     * @param string $message
     */
    public function assertElementPresent($xpath, $message = '')
    {
        PHPUnit_Framework_Assert::assertTrue(
            $this->isElementPresent($xpath),
            $message
        );
    }

    /**
     * Clear input element when standard clear() does not help
     *
     * @param $element \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected function clearInput($element)
    {
        $element->value('');
        $tx = $element->value();
        while ($tx!="") {
            $this->keysSpecial('backspace');
            $tx = $element->value();
        }
    }
}
