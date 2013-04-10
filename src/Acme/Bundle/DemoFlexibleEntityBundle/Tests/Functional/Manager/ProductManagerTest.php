<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Functional\Manager;

use Acme\Bundle\DemoFlexibleEntityBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductManagerTest extends WebTestCase
{
    protected $client = null;

    /**
     * @var FlexibleManager
     */
    protected $manager;

    public function setUp()
    {
        $this->client = static::createClient(array());
        $this->manager = $this->client->getContainer()->get('product_manager');
    }

    public function testCreateEntity()
    {
        $newProduct = $this->manager->createFlexible();
        $this->assertTrue($newProduct instanceof Product);

        $sku = 'my sku '.str_replace('.', '', microtime(true));
        $newProduct->setSku($sku);
        $this->assertEquals($sku, $newProduct->getSku());
    }
}
