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
class ProductControllerTest extends KernelAwareControllerTest
{

    /**
     * Define product controller name for url generation
     * @staticvar string
     */
    protected static $controller = 'product';

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
     * Insert attributes, products and translate them
     */
    protected function loadProducts()
    {
        $this->client->request('GET', self::prepareUrl('en', 'product', 'loader'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', self::prepareUrl('en', 'productattribute', 'loader'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', self::prepareUrl('en', 'producttranslate', 'loader'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testViewAction()
    {
        // insert attributes data then products data
        $this->loadProducts();

        $this->client->request('GET', self::prepareUrl('en', 'view/1'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', self::prepareUrl('fr', 'view/1'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testAttributeAction()
    {
        // insert attributes data then products data
        $this->loadProducts();

        $this->client->request('GET', self::prepareUrl('en', 'attribute'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test query actions
     */
    public function testQueries()
    {
        // insert attributes data then products data
        $this->loadProducts();

        $actions = array(
            'querylazyload',
            'queryonlyname',
            'querynameanddesc',
            'querynameanddescforcelocale',
            'queryfilterskufield',
            'querynamefilterskufield',
            'queryfiltersizeattribute',
            'queryfiltersizeanddescattributes',
            'querynameanddesclimit',
            'querynameanddescorderby',
        );
        foreach ($actions as $action) {
            $this->client->request('GET', self::prepareUrl('en', $action));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Test query actions without data
     */
    public function testQueriesWithoutData()
    {
        // actions returning code 200
        $actions = array(
            'querylazyload',
            'queryfilterskufield',
        );
        foreach ($actions as $action) {
            $this->client->request('GET', self::prepareUrl('en', $action));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }

        // actions returning exception
        $actions = array(
            'queryonlyname',
            'querynameanddesc',
            'querynameanddescforcelocale',
            'querynamefilterskufield',
            'queryfiltersizeattribute',
            'queryfiltersizeanddescattributes',
            'querynameanddesclimit',
            'querynameanddescorderby',
        );
        foreach ($actions as $action) {
            $this->client->request('GET', self::prepareUrl('en', $action));
            $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        }
    }

}
