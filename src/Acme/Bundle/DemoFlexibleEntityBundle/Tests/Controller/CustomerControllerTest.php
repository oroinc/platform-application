<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

use Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller\AbstractControllerTest;

/**
 * Test related class
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CustomerControllerTest extends AbstractControllerTest
{

    /**
     * Define customer controller name for url generation
     * @staticvar string
     */
    protected static $controller = 'customer';

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
        $this->loadCustomers();

        $this->client->request('GET', self::prepareUrl('en', 'index'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', self::prepareUrl('fr', 'index'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testViewAction()
    {
        // insert attributes data then customers data
        $this->loadCustomers();

        // call and assert view
        $this->client->request('GET', self::prepareUrl('en', 'view/1'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testAttributeAction()
    {
        // insert attributes data then customers data
        $this->loadCustomers();

        $this->client->request('GET', self::prepareUrl('en', 'attribute'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Load customer attributes then customer data
     */
    protected function loadCustomers()
    {
        $this->client->request('GET', self::prepareUrl('en', 'customerattribute', 'loader'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', self::prepareUrl('en', 'customer', 'loader'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test query actions
     */
    public function testQueries()
    {
        // insert attributes data then customers data
        $this->loadCustomers();

        $actions = array(
            'querylazyload',
            'queryonlydob',
            'queryonlydobandgender',
            'queryfilterfirstname',
            'queryfilterfirstnameandcompany',
            'queryfilterfirstnameandlimit',
            'queryfilterfirstnameandorderbirthdatedesc'
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
            'queryfilterfirstname',
            'queryfilterfirstnameandlimit',
        );
        foreach ($actions as $action) {
            $this->client->request('GET', self::prepareUrl('en', $action));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }

        // actions returning exception
        $actions = array(
            'queryonlydob',
            'queryonlydobandgender',
            'queryfilterfirstnameandcompany',
            'queryfilterfirstnameandorderbirthdatedesc'
        );
        foreach ($actions as $action) {
            $this->client->request('GET', self::prepareUrl('en', $action));
            $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        }
    }

}
