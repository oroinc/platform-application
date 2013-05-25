<?php

namespace Acme\Bundle\DemoAddressBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Oro\Bundle\AddressBundle\Form\Type\AddressType as AddressTypeBase;

class AddressType extends AddressTypeBase
{
    /**
     * {@inheritdoc}
     */
    public function addEntityFields(FormBuilderInterface $builder)
    {
        parent::addEntityFields($builder);

        $builder->get('postalCode')->setRequired(false);

        $builder->add(
            'state',
            'oro_region',
            array('required' => true)
        );
        $builder->add('working_hours', 'text', array('required' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_address_service';
    }
}
