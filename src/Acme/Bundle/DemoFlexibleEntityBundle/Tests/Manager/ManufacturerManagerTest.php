<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Test\Service;

use Acme\Bundle\DemoFlexibleEntityBundle\Tests\KernelAwareTest;

use Acme\Bundle\DemoFlexibleEntityBundle\Entity\Manufacturer;

/**
 * Test related class
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ManufacturerManagerTest extends KernelAwareTest
{

    /**
     * @var SimpleManager
     */
    protected $manager;

    /**
     * UT set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->container->get('manufacturer_manager');
    }

    /**
     * Test related method
     */
    public function testInsert()
    {
        $newManufacturer = $this->manager->createEntity();
        $this->assertTrue($newManufacturer instanceof Manufacturer);
        $newManufacturer->setName('Lenovo');
    }
}
