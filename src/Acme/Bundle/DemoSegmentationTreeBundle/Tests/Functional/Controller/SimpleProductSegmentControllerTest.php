<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests for the simple product Segment controller
 *
 * @author    Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
class SimpleProductSegmentControllerTest extends WebTestCase
{
    protected $client;

    public function runCommand($command, Array $options = array())
    {
        $kernel = $this->client->getKernel();
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);

        $runOptions = array(
            "command" => $command,
            "--quiet" => null,
            "--env" => "test"
        );

        $runOptions = array_merge($runOptions, $options);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($runOptions));
    }

    public function setUp()
    {
        $this->client = static::createClient(array("debug"=>false));
        $kernel = $this->client->getKernel();

        $fixturesPath = $kernel->locateResource('@AcmeDemoSegmentationTreeBundle/Tests/Functional/DataFixtures/ORM/SimpleProductSegment/');
        $options = array(
            '--force' => null
        );
        $this->runCommand("doctrine:schema:drop",$options);

        $options = array();
        $this->runCommand("doctrine:schema:create", $options);

        $options = array(
            '--fixtures' => $fixturesPath,
            '--no-interaction' => null,
        );
        $this->runCommand("doctrine:fixtures:load",$options);
    }


    public function testGetChildren()
    {
        $this->client->request(
            'GET',
            '/segmentation-tree/simple-product-segment/children',
            array(),
            array(),
            array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
            )
        );
        $response = $this->client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $this->assertEquals('[{"attr":{"id":"node_7","rel":"folder"},"data":"components","state":"closed"},{"attr":{"id":"node_1","rel":"folder"},"data":"computers","state":"closed"}]',$response->getContent());
    }
 
}
