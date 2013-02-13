<?php

namespace Acme\Bundle\DemoSearchBundle\Tests\Functional\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class RestApiTest extends WebTestCase
{

    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @param string $request
     * @param array $response
     *
     * @dataProvider requestsApi
     */
    public function testApi($request, $response)
    {
        $requestUrl = '';
        foreach ($request as $key => $value) {
            $requestUrl .= (is_null($request[$key])) ? '' :
                (($requestUrl!=='') ? '&':'') . "{$key}=" . $value;
        }
        $this->client->request('GET', "api/rest/latest/search?search={$requestUrl}");

        $result = $this->client->getResponse();

        $this->assertJsonResponse($result, 200);
        $result = json_decode($result->getContent(), true);
        //compare result
        $this->assertEqualsResponse($response, $result);
    }

    /**
     * Data provider for REST API tests
     *
     * @return array
     */
    public function requestsApi()
    {
        $parameters = array();
        $testFiles = new RecursiveDirectoryIterator(
            __DIR__ . DIRECTORY_SEPARATOR . 'requests',
            RecursiveDirectoryIterator::SKIP_DOTS
        );
        foreach ($testFiles as $fileName => $object) {
            $parameters[$fileName] = Yaml::parse($fileName);
            if (is_null($parameters[$fileName]['response']['data'])) {
                unset($parameters[$fileName]['response']['data']);
            }
        }
        return
            $parameters;
    }

    protected function tearDown()
    {
        unset($this->client);
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
            $statusCode,
            $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    /**
     * Test API response
     *
     * @param array $response
     * @param array $result
     */
    protected function assertEqualsResponse($response, $result)
    {
        $this->assertEquals($response['records_count'], $result['records_count']);
        $this->assertEquals($response['count'], $result['count']);
        if (isset($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $key => $object) {
                foreach ($object as $property => $value) {
                    $this->assertEquals($value, $result['data'][$key][$property]);
                }
            }
        }
    }
}
