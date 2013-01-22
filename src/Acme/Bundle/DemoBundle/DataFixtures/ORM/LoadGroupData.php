<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\UserBundle\Entity\Group;

class LoadGroupData extends AbstractFixture
{
    /**
     * Load sample user group data
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist(new Group('Users', ['ROLE_USER']));
        $manager->persist(new Group('Managers', ['ROLE_USER']));
        $manager->persist(new Group('Administrators', ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']));

        $manager->flush();
    }
}
