<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Acme\Bundle\DemoBundle\Entity\Manufacturer;

class LoadManufacturesData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Fila');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_FILA', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Converse');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_CONVERSE', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('New Balance');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_NEW_BALANCE', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('K-Swiss');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_KSWISS', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Asics');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_ASICS', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Hi-Tec');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_HITEC', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('The North Face');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_NORTH_FACE', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('L.L. Bean');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_LLBEAN', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Under Armour');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_UNDER_ARMOUR', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Quicksilver');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_QUICKSILVER', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Lacoste');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_LACOSTE', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Ubmro');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_UMBRO', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Mckenzie');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_MCKENZIE', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Carbrini');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_CARBRINI', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Bench');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_BENCH', $manufacturer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('Timberland');
        $manager->persist($manufacturer);
        $this->addReference('MANUFACTURER_TIMBERLAND', $manufacturer);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
