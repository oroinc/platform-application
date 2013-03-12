<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Functional\Controller;

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
            $this->client->request(
                'GET',
                self::prepareUrl($locale, 'index'),
                array(),
                array(),
                array('PHP_AUTH_USER' =>  self::AUTH_USER, 'PHP_AUTH_PW' => self::AUTH_PW)
            );
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Test related method
     */
    public function testEntityConfigAction()
    {
        foreach (self::$locales as $locale) {
            $this->client->request(
                'GET',
                self::prepareUrl($locale, 'config'),
                array(),
                array(),
                array('PHP_AUTH_USER' =>  self::AUTH_USER, 'PHP_AUTH_PW' => self::AUTH_PW)
            );
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }
}
