<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;
use Oro\Bundle\UserBundle\Entity\User;

class LoadUserAttrData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load sample user group data
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /**
         * @var Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleEntityManager
         */
        $fm = $this->container->get('oro_user.flexible_manager');
        $sm = $fm->getStorageManager();

        $attr = $fm->createAttribute()
            ->setCode('firstname')
            ->setBackendType(AbstractAttributeType::BACKEND_TYPE_VARCHAR);

        $sm->persist($attr);

        $attr = $fm->createAttribute()
            ->setCode('lastname')
            ->setBackendType(AbstractAttributeType::BACKEND_TYPE_VARCHAR);

        $sm->persist($attr);

        $attr = $fm->createAttribute()
            ->setCode('salary')
            ->setBackendType(AbstractAttributeType::BACKEND_TYPE_INTEGER);

        $sm->persist($attr);

        $attr = $fm->createAttribute()
            ->setCode('birthday')
            ->setBackendType(AbstractAttributeType::BACKEND_TYPE_DATE);

        $sm->persist($attr);

        $attr = $fm->createAttribute()
            ->setCode('gender')
            ->setBackendType(AbstractAttributeType::BACKEND_TYPE_OPTION)
            ->addOption(
                $fm->createAttributeOption()->addOptionValue(
                    $fm->createAttributeOptionValue()->setValue('Male')
                )
            )
            ->addOption(
                $fm->createAttributeOption()->addOptionValue(
                    $fm->createAttributeOptionValue()->setValue('Female')
                )
            );

        $sm->persist($attr);
        $sm->flush();
    }
}
