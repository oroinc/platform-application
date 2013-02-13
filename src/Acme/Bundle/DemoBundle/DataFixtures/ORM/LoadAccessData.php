<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\UserBundle\Entity\Group;
use Oro\Bundle\UserBundle\Entity\Role;

class LoadAccessData extends AbstractFixture
{
    /**
     * Load sample user roles and group data
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roleManager = new Role('ROLE_MANAGER');

        $roleManager->setLabel('Manager');

        $roleAdmin = new Role('ROLE_ADMIN');

        $roleAdmin->setLabel('Administrator');

        $roleSuper = new Role('ROLE_SUPER_ADMIN');

        $roleSuper->setLabel('Superman');

        $manager->persist($roleManager);
        $manager->persist($roleAdmin);
        $manager->persist($roleSuper);

        $manager->persist(new Group('Managers', array($roleManager)));
        $manager->persist(new Group('Administrators', array($roleAdmin, $roleSuper)));

        $manager->flush();
    }
}
