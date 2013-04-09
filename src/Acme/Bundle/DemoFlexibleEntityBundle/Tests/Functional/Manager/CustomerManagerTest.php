<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Functional\Manager;

use Acme\Bundle\DemoFlexibleEntityBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerManagerTest extends WebTestCase
{
    protected $client = null;

    /** Flexible Manager */
    protected $manager;

    public function setUp()
    {
        $this->client = static::createClient(array());
        $this->manager = $this->client->getContainer()->get('customer_manager');
    }

    public function testCreateEntity()
    {
        $newCustomer = $this->manager->createFlexible();
        $this->assertTrue($newCustomer instanceof Customer);
        $newCustomer->setFirstname('FirstNameTest');
        $newCustomer->setLastname('LastNameTest');
        $this->assertEquals($newCustomer->getFirstname(), 'FirstNameTest');
    }
}
