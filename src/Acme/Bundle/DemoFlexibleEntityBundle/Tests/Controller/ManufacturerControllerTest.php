<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

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
     * {@inheritdoc}
     */
    protected function getFixturesToLoad()
    {
        return array(
                'src/Acme/Bundle/DemoFlexibleEntityBundle/DataFixtures/ORM/Manufacturer'
        );
    }

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

}
