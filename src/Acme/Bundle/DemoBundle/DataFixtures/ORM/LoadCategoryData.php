<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Acme\Bundle\DemoBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load sample data
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('boots');
        $manager->persist($category);
        $this->addReference('CATEGORY_BOOTS', $category);

        $category = new Category();
        $category->setName('tracking shoes');
        $manager->persist($category);
        $this->addReference('CATEGORY_tracking', $category);

        $category = new Category();
        $category->setName('mocasines');
        $manager->persist($category);
        $this->addReference('CATEGORY_mocasines', $category);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
