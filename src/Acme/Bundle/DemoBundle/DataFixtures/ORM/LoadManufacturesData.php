<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Acme\Bundle\DemoBundle\Entity\Manufacturer;

class LoadManufacturerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load sample manufactures data
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manufacturer = new Manufacturer();
        $manufacturer->setName('Adidas');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_ADIDAS', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Reebok');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_REEBOK', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Nike');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_NIKE', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Puma');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_PUMA', $manufacturer);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
