<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

use Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller\KernelAwareControllerTest;

/**
 * Test related class
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ManufacturerControllerTest extends KernelAwareControllerTest
{

    /**
     * Define manufacturer controller name for url generation
     * @staticvar string
     */
    protected static $controller = 'manufacturer';

    /**
     * Test related method
     */
    public function testIndexAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'index'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', self::prepareUrl('fr', 'index'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // load data then test the same urls
        $this->loadManufacturers();

        $this->client->request('GET', self::prepareUrl('en', 'index'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', self::prepareUrl('fr', 'index'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Load manufacturers
     */
    protected function loadManufacturers()
    {
        $this->client->request('GET', self::prepareUrl('en', 'manufacturer', 'loader'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

}
