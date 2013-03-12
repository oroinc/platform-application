<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Functional\Controller;

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
}
