<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Acme\Bundle\DemoBundle\Entity\Manufacturer;

class LoadManufacturesData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load sample manufactures data
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manufactures = array(
            'Adidas'         => 'MANUFACTURER_ADIDAS',
            'Reebok'         => 'MANUFACTURER_REEBOK',
            'Nike'           => 'MANUFACTURER_NIKE',
            'Puma'           => 'MANUFACTURER_PUMA',
            'Fila'           => 'MANUFACTURER_FILA',
            'Converse'       => 'MANUFACTURER_CONVERSE',
            'New Balance'    => 'MANUFACTURER_NEW_BALANCE',
            'K-Swiss'        => 'MANUFACTURER_KSWISS',
            'Asics'          => 'MANUFACTURER_ASICS',
            'Hi-Tec'         => 'MANUFACTURER_HITEC',
            'The North Face' => 'MANUFACTURER_NORTH_FACE',
            'L.L. Bean'      => 'MANUFACTURER_LLBEAN',
            'Under Armour'   => 'MANUFACTURER_UNDER_ARMOUR',
            'Quicksilver'    => 'MANUFACTURER_QUICKSILVER',
            'Lacoste'        => 'MANUFACTURER_LACOSTE',
            'Ubmro'          => 'MANUFACTURER_UMBRO',
            'Mckenzie'       => 'MANUFACTURER_MCKENZIE',
            'Carbrini'       => 'MANUFACTURER_CARBRINI',
            'Bench'          => 'MANUFACTURER_BENCH',
            'Timberland'     => 'MANUFACTURER_TIMBERLAND'
        );

        foreach ($manufactures as $manufacturer => $reference) {
            $this->loadManufacturer($manufacturer, $reference, $manager);
        }

        $manager->flush();
    }

    private function loadManufacturer($name, $reference, ObjectManager $manager)
    {
        $manufacturer = new Manufacturer();
        $manufacturer->setName($name);
        $manager->persist($manufacturer);
        $this->addReference($reference, $manufacturer);
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
