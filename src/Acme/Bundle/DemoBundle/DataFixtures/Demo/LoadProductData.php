<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Acme\Bundle\DemoBundle\Entity\Product;
use Acme\Bundle\DemoBundle\Entity\Manufacturer;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var Manufacturer[]
     */
    protected $manufacturers;

    /**
     * @return Manufacturer
     */
    protected function getRandomManufacturer()
    {
        if (null === $this->manufacturers) {
            $this->manufacturers = array(
                $this->getReference('MANUFACTURER_ADIDAS'),
                $this->getReference('MANUFACTURER_REEBOK'),
                $this->getReference('MANUFACTURER_NIKE'),
                $this->getReference('MANUFACTURER_PUMA'),
                $this->getReference('MANUFACTURER_FILA'),
                $this->getReference('MANUFACTURER_CONVERSE'),
                $this->getReference('MANUFACTURER_NEW_BALANCE'),
                $this->getReference('MANUFACTURER_KSWISS'),
                $this->getReference('MANUFACTURER_ASICS'),
                $this->getReference('MANUFACTURER_HITEC'),
                $this->getReference('MANUFACTURER_NORTH_FACE'),
                $this->getReference('MANUFACTURER_LLBEAN'),
                $this->getReference('MANUFACTURER_UNDER_ARMOUR'),
                $this->getReference('MANUFACTURER_QUICKSILVER'),
                $this->getReference('MANUFACTURER_LACOSTE'),
                $this->getReference('MANUFACTURER_UMBRO'),
                $this->getReference('MANUFACTURER_MCKENZIE'),
                $this->getReference('MANUFACTURER_BENCH'),
                $this->getReference('MANUFACTURER_TIMBERLAND'),
            );
        }

        $key = array_rand($this->manufacturers);
        return $this->manufacturers[$key];
    }

    /**
     * Load sample manufactures data
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 50; $i++) {
            $createdDate = new \DateTime('now', new \DateTimeZone('UTC'));
            $createdDate->sub(new \DateInterval('P' . $i . 'D'));

            $product = new Product();
            $product->setName('Product ' . $i)
                ->setPrice(rand(5, 15))
                ->setCount(rand(15, 25))
                ->setDescription('Description ' . $i)
                ->setCreateDate($createdDate)
                ->setManufacturer($this->getRandomManufacturer());
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
