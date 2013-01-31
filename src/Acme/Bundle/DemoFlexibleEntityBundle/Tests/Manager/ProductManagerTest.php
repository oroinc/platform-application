<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Test\Manager;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\IntegerType;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;

use Acme\Bundle\DemoFlexibleEntityBundle\Entity\Product;
use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;

use Acme\Bundle\DemoFlexibleEntityBundle\Tests\KernelAwareTest;

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
    public function testcreateEntity()
    {
        $newProduct = $this->manager->createFlexible();
        $this->assertTrue($newProduct instanceof Product);

        $sku = 'my sku '.str_replace('.', '', microtime(true));
        $newProduct->setSku($sku);
    }

    /**
     * Test related method
     */
    public function testGetNewValueInstance()
    {
        $timestamp = str_replace('.', '', microtime(true));

        // entity
        $newProduct = $this->manager->createFlexible();
        $this->assertTrue($newProduct instanceof Product);
        $sku = 'my-sku-'.$timestamp;
        $newProduct->setSku($sku);

        // attribute name
        $attName = $this->manager->createAttribute(new TextType());
        $attNameCode= 'name'.$timestamp;
        $attName->setCode($attNameCode);
        $attName->setTranslatable(true);
        $this->manager->getStorageManager()->persist($attName);

        // attribute size
        $attSize = $this->manager->createAttribute(new IntegerType());
        $attSizeCode= 'size'.$timestamp;
        $attSize->setCode($attSizeCode);
        $this->manager->getStorageManager()->persist($attSize);

        // name value
        $valueName = $this->manager->createFlexibleValue();
        $valueName->setAttribute($attName);
        $valueName->setData('my name');
        $newProduct->addValue($valueName);

        // size value
        $valueSize = $this->manager->createFlexibleValue();
        $valueSize->setAttribute($attSize);
        $valueSize->setData(125);
        $newProduct->addValue($valueSize);

        // required name attribute
        $attRequiredName = $this->manager->getFlexibleRepository()->findAttributeByCode('name');
        $valueRequiredName = $this->manager->createFlexibleValue();
        $valueRequiredName->setAttribute($attRequiredName);
        $valueRequiredName->setData('my name');
        $newProduct->addValue($valueRequiredName);

        // persist
        $this->manager->getStorageManager()->persist($newProduct);
        $this->manager->getStorageManager()->flush();

        // remove product inserted
        $this->manager->getStorageManager()->remove($attName);
        $this->manager->getStorageManager()->remove($attSize);
        $this->manager->getStorageManager()->remove($newProduct);
        $this->manager->getStorageManager()->flush();
    }
}
