<?php

namespace Acme\Bundle\DemoBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\FlexibleType;

class ProductType extends FlexibleType
{
    public function addEntityFields(FormBuilderInterface $builder)
    {
        parent::addEntityFields($builder);

        $builder->add('name');
        $builder->add('price');
        $builder->add('description');
        $builder->add('count');
        $builder->add('createDate');
        $builder->add(
            'manufacturer',
            'entity',
            array(
                'class' => "Acme\\Bundle\\DemoBundle\\Entity\\Manufacturer",
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            )
        );
        $builder->add(
            'categories',
            'entity',
            array(
               'class' => "Acme\\Bundle\\DemoBundle\\Entity\\Category",
               'multiple' => true,
               'expanded' => false,
               'required' => true,
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->flexibleClass,
            )
        );
    }

    public function getName()
    {
        return 'oro_bundle_databundle_producttype';
    }
}
