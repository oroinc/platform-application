<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Functional\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\Bundle\TestsBundle\Test\ToolsAPI;

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
     * List of locales to test
     * @staticvar multitype:string
     */
    protected static $locales = array('en');

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

        //echo "\nURL --> ". $url ."\n";

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        // initialize kernel, container and client
        $this->client = static::createClient(array('debug' => false), ToolsAPI::generateBasicHeader());

        $this->initializeDatabase();
    }

    /**
     * Initialize database dropping existent and create tables
     * @param boolean $appendData if false drop database and insert fixtures
     */
    protected function initializeDatabase($appendData = false)
    {
        $fixtures = $this->getFixturesToLoad();

        foreach ($fixtures as $fixture) {
            $fixture->setContainer($this->getContainer());
            $fixture->load($this->getContainer()->get('doctrine.orm.entity_manager'));
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
    public function tearDown()
    {
        $this->truncateDatabase();

        parent::tearDown();
    }

    /**
     * Truncate tables from database (can be usefull if use append param of fixtures)
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
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getStorageManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * Get tables to truncate
     * Must be redefine to truncate a minimum of tables
     *
     * @return array
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
