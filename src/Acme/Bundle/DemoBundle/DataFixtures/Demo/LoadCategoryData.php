<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Acme\Bundle\DemoBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
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
