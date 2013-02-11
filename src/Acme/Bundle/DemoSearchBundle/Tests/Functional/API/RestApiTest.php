<?php

namespace Acme\Bundle\DemoSearchBundle\Test\Functional\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class RestApiTest extends WebTestCase
{
    protected $_client;

    public function setUp()
    {
        $this->_client = static::createClient();
    }

    /**
     * @param string $request
     * @param string $response
     *
     * @dataProvider requestsApi
     */
    public function testApi($request, $response)
    {
        $this->_client->request('GET', "api/rest/latest/search?search={$request}");

        $result = $this->_client->getResponse();

        $this->assertJsonResponse($result, 200);
        $this->assertEquals($response, $result->getContent());
    }

    /**
     * Data provider for REST API tests
     *
     * @return array
     */
    public function requestsApi() {
        $parameters = array();
        $testFiles = new RecursiveDirectoryIterator(__DIR__ . DIRECTORY_SEPARATOR . 'requests', RecursiveDirectoryIterator::CURRENT_AS_FILEINFO);
        foreach ($testFiles as $fileName => $object ) {
            $parameters[$fileName] = Yaml::parse($fileName);
            if (is_null($parameters[$fileName]['response']['records_set'])) {
                unset($parameters[$fileName]['response']['records_set']);
            }
            $parameters[$fileName]['response'] = json_encode($parameters[$fileName]['response']);
        }
        return
            $parameters;
    }

    protected function tearDown()
    {
        unset($this->_client);
    }

    /**
     * Test API response status
     *
     * @param string $response
     * @param int $statusCode
     */
    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

}
