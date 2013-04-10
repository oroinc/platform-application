<?php

// @codingStandardsIgnoreStart
class ScreenshotListener implements \PHPUnit_Framework_TestListener
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->storeAScreenshot($test);
    }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->storeAScreenshot($test);
    }

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        $this->storeAScreenshot($test);
    }

    private function storeAScreenshot(\PHPUnit_Framework_Test $test)
    {
        if ($test instanceof \PHPUnit_Extensions_Selenium2TestCase) {

            $className = explode('\\', get_class($test));
            try {
                $file = getcwd() . DIRECTORY_SEPARATOR . $this->directory . DIRECTORY_SEPARATOR . end($className);
                $file .= '__' . $test->getName() . '__ ' . date('Y-m-d\TH-i-s') . '.png';
                file_put_contents($file, $test->currentScreenshot());
            } catch (\Exception $e) {

                $file = getcwd() . DIRECTORY_SEPARATOR . $this->directory . DIRECTORY_SEPARATOR . end($className);
                $file .= '__' . $test->getName() . '__ ' . date('Y-m-d\TH-i-s') . '.txt';
                file_put_contents(
                    $file,
                    "Screenshot generation doesn't work." . "\n" . $e->getMessage() . "\n" . $e->getTraceAsString()
                );
            }
        }
    }

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {

    }

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {

    }

    public function startTest(\PHPUnit_Framework_Test $test)
    {

    }

    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {

    }

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {

    }
}
