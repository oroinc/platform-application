<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

use Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller\AbstractControllerTest;

/**
 * Test related class
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class LoaderControllerTest extends AbstractControllerTest
{

    /**
     * Define loader controller name for url generation
     * @staticvar string
     */
    protected static $controller = 'loader';

    /**
     * Test related method
     */
    public function testCustomerAttributeAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'customerattribute'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        // second time to test already exists messages
        $this->client->request('GET', self::prepareUrl('en', 'customerattribute'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testProductAttributeAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'productattribute'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        // second time to test already exists messages
        $this->client->request('GET', self::prepareUrl('en', 'productattribute'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testCustomerAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'customer'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testManufacturerAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'manufacturer'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testProductAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'product'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testProductTranslateAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'producttranslate'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
    public function testTruncateDbAction()
    {
        $this->client->request('GET', self::prepareUrl('en', 'truncatedb'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

}
