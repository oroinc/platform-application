<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

use Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller\KernelAwareControllerTest;

/**
 * Test related class
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class FlexibleControllerTest extends KernelAwareControllerTest
{

    /**
     * Define flexible controller name for url generation
     * @staticvar string
     */
    protected static $controller = 'flexible';

    /**
     * Test related method
     */
    public function testIndexAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'index'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', self::prepareUrl('fr', 'index'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testEntityConfigAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'entityconfig'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

}
