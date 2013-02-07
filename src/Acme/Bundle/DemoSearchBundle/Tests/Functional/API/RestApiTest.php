<?php

namespace Acme\Bundle\DemoSearchBundle\Test\Functional\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestApiTest extends WebTestCase
{
    protected $_client;

    public function setUp()
    {
        $this->_client = static::createClient();
    }

    public function testApi()
    {
        $this->_client->request('GET', 'api/rest/latest/search');

        $response = $this->_client->getResponse();

        $this->assertJsonResponse($response, 200);
    }

    protected function tearDown()
    {
        unset($this->_client);
    }

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
