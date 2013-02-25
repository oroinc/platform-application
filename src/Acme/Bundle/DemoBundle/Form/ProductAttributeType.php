<?php
namespace Acme\Bundle\DemoBundle\Form;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\AttributeType;

use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;

use Symfony\Component\Form\FormBuilderInterface;

class ProductAttributeType extends AttributeType
{
    /**
     * Add field scopable to form builder
     * @param FormBuilderInterface $builder
     */
    protected function addFieldScopable(FormBuilderInterface $builder)
    {
        // not using scope for customer attributes
        $builder->add('scopable', 'hidden', array('data' => 0));
    }
}