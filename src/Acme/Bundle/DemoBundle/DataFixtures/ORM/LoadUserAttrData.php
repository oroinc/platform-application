<?php

namespace Acme\Bundle\DemoBundle\DataFixtures\ORM;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionSimpleSelectType;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\DateType;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MoneyType;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;

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
         * @var Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager
         */
        $fm = $this->container->get('oro_user.flexible_manager');
        $sm = $fm->getStorageManager();

        $attr = $fm->createAttribute(new TextType())
            ->setCode('firstname');

        $sm->persist($attr);

        $attr = $fm->createAttribute(new TextType())
            ->setCode('lastname');

        $sm->persist($attr);

        $attr = $fm->createAttribute(new MoneyType())
            ->setCode('salary');

        $sm->persist($attr);

        $attr = $fm->createAttribute(new DateType())
            ->setCode('birthday');

        $sm->persist($attr);

        $attr = $fm->createAttribute(new OptionSimpleSelectType())
            ->setCode('gender')
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
