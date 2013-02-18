<?php

namespace Acme\Bundle\DemoBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\FlexibleType;

class ProductType extends FlexibleType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::addEntityFields($builder);
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('count')
            ->add('createDate')
            ->add('manufacturer', 'entity', array(
                'class' => "Acme\\Bundle\\DemoBundle\\Entity\\Manufacturer",
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ))
            ->add('categories', 'entity', array(
               'class' => "Acme\\Bundle\\DemoBundle\\Entity\\Category",
               'multiple' => true,
               'expanded' => false,
               'required' => true,
            ))
        ;
    }
}
