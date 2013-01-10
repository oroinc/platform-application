<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

use Symfony\Component\Console\Input\StringInput;

use Symfony\Bundle\FrameworkBundle\Console\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Abstract controller web test case
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @abstract
 */
abstract class AbstractControllerTest extends WebTestCase
{

    /**
     * Tested url pattern
     * @staticvar string
     */
    static protected $testedUrl = '/%%lang%%/flexible-entity/%%controller%%/%%action%%';

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    /**
     * Generate url to testing
     * @param string $locale     locale tested
     * @param string $action     action tested
     * @param string $controller controller tested, defined in test class but can be override for a specific call
     *
     * @return string
     *
     * @static
     */
    protected static function prepareUrl($locale, $action, $controller = null)
    {
        $controller = ($controller === null) ? static::$controller : $controller;

        $url = self::$testedUrl;

        $url = str_replace('%%lang%%', $locale, $url);
        $url = str_replace('%%controller%%', $controller, $url);
        $url = str_replace('%%action%%', $action, $url);

        echo $url ."\n";

        return $url;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Console\Application
     *
     * @static
     */
    protected static function getApplication()
    {
        $client = static::createClient();

        $application = new Application($client->getKernel());
        $application->setAutoExit(false);

        return $application;
    }

    /**
     * Launch a command line
     * @param string $command
     *
     * @return integer 0 if everything went fine, or an error code
     */
    protected function runCommand($command)
    {
        $input = new StringInput($command);

        return self::getApplication()->run($input);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // reinitialize database
        $this->initializeDatabase();

        parent::tearDown();
    }

    /**
     * Initialize database dropping existent and create tables
     */
    protected function initializeDatabase()
    {
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
    }

    /**
     * {@inheritdoc}
     */
    public function runTest()
    {
        $this->client = static::createClient();

        parent::runTest();
    }

    /**
     * Get container
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer()
    {
        return static::$kernel->getContainer();
    }
}
