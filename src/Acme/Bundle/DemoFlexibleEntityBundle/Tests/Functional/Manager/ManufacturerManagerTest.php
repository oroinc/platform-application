<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Functional\Service;

use Acme\Bundle\DemoFlexibleEntityBundle\Entity\Manufacturer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ManufacturerManagerTest extends WebTestCase
{
    protected $client = null;

    /**
     * @var SimpleManager
     */
    protected $manager;

    public function setUp()
    {
        $this->client = static::createClient(array());
        $this->manager = $this->client->getContainer()->get('manufacturer_manager');
    }

    public function testCreateEntity()
    {
        $newManufacturer = $this->manager->createEntity();
        $this->assertTrue($newManufacturer instanceof Manufacturer);
        $newManufacturer->setName('Lenovo');
    }
}
