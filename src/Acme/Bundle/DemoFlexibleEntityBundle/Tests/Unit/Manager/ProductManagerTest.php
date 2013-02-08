<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Test\Unit\Manager;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\IntegerType;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;

use Acme\Bundle\DemoFlexibleEntityBundle\Entity\Product;

use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;

use Acme\Bundle\DemoFlexibleEntityBundle\Tests\Unit\KernelAwareTest;

/**
 * Test related class
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ProductManagerTest extends KernelAwareTest
{

    /**
     * @var FlexibleManager
     */
    protected $manager;

    /**
     * UT set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->container->get('product_manager');
    }

    /**
     * Test related method
     */
    public function testCreateEntity()
    {
        $newProduct = $this->manager->createFlexible();
        $this->assertTrue($newProduct instanceof Product);

        $sku = 'my sku '.str_replace('.', '', microtime(true));
        $newProduct->setSku($sku);
        $this->assertEquals($sku, $newProduct->getSku());
    }
}
