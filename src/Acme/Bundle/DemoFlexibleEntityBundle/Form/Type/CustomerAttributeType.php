<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Form\Type;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\AttributeType;

use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

/**
 * Type for attribute form
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CustomerAttributeType extends AttributeType
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
