<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Oro\Bundle\UserBundle\Entity\Acl;

class LoadAclData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load Root ACL Resource
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $rootAcl = new Acl();
        $rootAcl->setId('template_controller')
            ->setDescription('Actions from template controller')
            ->setName('Template controller')
            ->setParent($this->getReference('acl_root'))
            ->addAccessRole($this->getReference('user_role'))
            ->addAccessRole($this->getReference('admin_role'));
        $manager->persist($rootAcl);
        $this->setReference('acl_template_controller', $rootAcl);
        $manager->flush();
    }

    public function getOrder()
    {
        return 101;
    }
}
