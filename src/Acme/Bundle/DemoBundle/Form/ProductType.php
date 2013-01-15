<?php

namespace Acme\Bundle\DemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\Bundle\DemoBundle\Entity\Product',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'oro_bundle_databundle_producttype';
    }
}
