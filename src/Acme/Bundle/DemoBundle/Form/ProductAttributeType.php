<?php
namespace Acme\Bundle\DemoBundle\Form;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\AttributeType;

use Symfony\Component\Form\FormBuilderInterface;

class ProductAttributeType extends AttributeType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     */
    protected function addFieldScopable(FormBuilderInterface $builder)
    {
        $builder->add('scopable', 'hidden', array('data' => 0));
    }
}
