<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Console\Input\ArrayInput;

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
abstract class KernelAwareControllerTest extends WebTestCase
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

        echo "\nURL --> ". $url ."\n";

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
     * @param string $command command name to run
     * @param array  $args    args for the command to run
     *
     * @return integer 0 if everything went fine, or an error code
     */
    protected function runCommand($command, $args = array())
    {
        // command name must be the first argument
        $args[0] = $command;
        $input = new ArrayInput(
            $args
        );

        return self::getApplication()->run($input);
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->initializeDatabase();
    }

    /**
     * Initialize database dropping existent and create tables
     */
    protected function initializeDatabase()
    {
        $fixtures = $this->getFixturesToLoad();

        if (!empty($fixtures)) {
            $args['--fixtures'] = $fixtures;
            $args['--no-interaction'] = true;
            $args['--append'] = true;

            $command = 'doctrine:fixtures:load';

            self::runCommand($command, $args);
        }
    }

    /**
     * @return array
     */
    protected function getFixturesToLoad()
    {
        return array();
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
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // truncate database
        $this->truncateDatabase();

        parent::tearDown();
    }

    /**
     * Truncate tables from database
     */
    public function truncateDatabase()
    {
        // get connection
        $connection = $this->getStorageManager()->getConnection();

        // get tables list
        $tables = $this->getTablesToTruncate();

        // truncate tables
        try {
            // start transaction
            $this->getStorageManager()->getConnection()->beginTransaction();

            // disable foreign keys check
            $connection->query('SET FOREIGN_KEY_CHECKS = 0');

            foreach ($tables as $table) {
                $query = $connection->getDatabasePlatform()->getTruncateTableSQL($table);
                $connection->executeUpdate($query);
            }

            // enable foreign key check
            $connection->query('SET FOREIGN_KEY_CHECKS = 0');

            $this->getStorageManager()->getConnection()->commit();
        } catch (\Exception $e) {
            // rollback if an exception is caught
            $connection->rollBack();
        }
    }

    /**
     * Get storage manager.. EntityManager by default
     * @return Doctrine\ORM\EntityManager
     */
    protected function getStorageManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * Get tables to truncate
     * Must be redefine to truncate a minimum of tables
     *
     * @return multitype:string
     */
    protected function getTablesToTruncate()
    {
        return array();
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
