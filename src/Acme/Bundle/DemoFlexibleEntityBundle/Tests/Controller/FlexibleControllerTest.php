<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

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
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl($locale, 'index'));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Test related method
     */
    public function testEntityConfigAction()
    {
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl($locale, 'config'));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

}
