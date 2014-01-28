<?php

namespace Acme\Bundle\DemoBundle\Migrations\DataFixtures\Demo\ORM\v1_0;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Acme\Bundle\DemoBundle\Entity\Product;
use Acme\Bundle\DemoBundle\Entity\Manufacturer;

class LoadProductData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * @var Manufacturer[]
     */
    protected $manufacturers;

    public function getDependencies()
    {
        return [
            'Acme\Bundle\DemoBundle\Migrations\DataFixtures\Demo\ORM\v1_0\LoadManufacturesData'
        ];
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
                ->setManufacturer($this->getRandomManufacturer($manager));
            $manager->persist($product);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @return Manufacturer
     */
    protected function getRandomManufacturer(ObjectManager $manager)
    {
        if (null === $this->manufacturers) {
            $this->manufacturers = array(
                $this->getManufacturer($manager, 'Adidas'),
                $this->getManufacturer($manager, 'Reebok'),
                $this->getManufacturer($manager, 'Nike'),
                $this->getManufacturer($manager, 'Puma'),
                $this->getManufacturer($manager, 'Fila'),
                $this->getManufacturer($manager, 'Converse'),
                $this->getManufacturer($manager, 'New Balance'),
                $this->getManufacturer($manager, 'K-Swiss'),
                $this->getManufacturer($manager, 'Asics'),
                $this->getManufacturer($manager, 'Hi-Tec'),
                $this->getManufacturer($manager, 'The North Face'),
                $this->getManufacturer($manager, 'L.L. Bean'),
                $this->getManufacturer($manager, 'Under Armour'),
                $this->getManufacturer($manager, 'Quicksilver'),
                $this->getManufacturer($manager, 'Lacoste'),
                $this->getManufacturer($manager, 'Ubmro'),
                $this->getManufacturer($manager, 'Mckenzie'),
                $this->getManufacturer($manager, 'Carbrini'),
                $this->getManufacturer($manager, 'Bench'),
                $this->getManufacturer($manager, 'Timberland')
            );
        }

        $key = array_rand($this->manufacturers);
        return $this->manufacturers[$key];
    }

    /**
     * @param ObjectManager $manager
     * @param $name
     * @return Manufacturer
     */
    protected function getManufacturer(ObjectManager $manager, $name)
    {
        return $manager->getRepository('AcmeDemoBundle:Manufacturer')->findOneBy(['name' => $name]);
    }
}
