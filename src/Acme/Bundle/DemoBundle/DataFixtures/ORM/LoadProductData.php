<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Acme\Bundle\DemoBundle\Entity\Product;
use Acme\Bundle\DemoBundle\Entity\Manufacturer;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Manufacturer[]
     */
    protected $manufacturers;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

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
                $this->getReference('MANUFACTURER_PUMA')
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
        for ($i = 1; $i <= 20; $i++) {
            $createdDate = new \DateTime('now');
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
