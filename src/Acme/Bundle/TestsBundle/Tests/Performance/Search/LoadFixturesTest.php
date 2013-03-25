<?php

namespace Acme\Bundle\TestsBundle\Tests\Performance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PerformanceTest extends WebTestCase
{
    const ENTITY_COUNT = 1000;

    protected $client;

    public function setUp()
    {
        $this->client = static::createClient(array("debug"=>false));
    }

    public function testSearchLoad()
    {
        //Load all fixtures
        $kernel = $this->client->getKernel();
        $container = $this->client->getContainer();
        $container->counter = self::ENTITY_COUNT;

        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);

        $options = array('command' => 'doctrine:fixtures:load');
        $options['--env'] = "test";
        $options['--no-interaction'] = null;
        $options['--no-debug'] = null;
        list($msec, $sec) = explode(" ", microtime());
        $start = $sec + $msec;

        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $options = array('command' => 'oro:search:reindex');
        $options['--env'] = "test";
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        list($msec, $sec) = explode(" ", microtime());
        $stop = $sec + $msec;
        $counter = $counter * 3;
        echo "\nUploading execution time of {$counter} entities is " . round($stop - $start, 4) . " sec";
    }

    protected function tearDown()
    {
        unset($this->client);
    }
}
