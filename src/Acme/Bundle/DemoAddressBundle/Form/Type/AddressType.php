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

        // address fields
        $builder
            ->add('street', 'text', $required)
            ->add('street2', 'text', $notRequired)
            ->add('city', 'text', $required)
            ->add('state', 'oro_region', $required)
            ->add('postalCode', 'text', $notRequired)
            ->add('country', 'oro_country', $required)
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
