<?php

namespace Acme\Bundle\DemoAddressBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\FlexibleType;
use Oro\Bundle\AddressBundle\Form\Type\AddressType as AddressTypeBase;

class AddressType extends AddressTypeBase
{
    /**
     * {@inheritdoc}
     */
    public function addEntityFields(FormBuilderInterface $builder)
    {
        // add default flexible fields
        FlexibleType::addEntityFields($builder);

        $required =  array(
            'required' => true,
        );
        $notRequired =  array(
            'required' => false,
        );

        // address fields
        $builder
            ->add('street', 'text', $required)
            ->add('street2', 'text', $notRequired)
            ->add('city', 'text', $required)
            ->add('state', 'text', $required)
            ->add('postal_code', 'text', $notRequired)
            ->add('country', 'text', $required)
            ->add('mark', 'text', $notRequired)
            ->add('working_hours', 'text', $notRequired);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_address_service';
    }
}
