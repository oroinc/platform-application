<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Form\Type;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\AttributeType;
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
class ProductAttributeType extends AttributeType
{

    /**
     * Add field scopable to form builder
     * @param FormBuilderInterface $builder
     */
    protected function addFieldScopable(FormBuilderInterface $builder)
    {
        // use custom scope notion pofor product
        $builder->add('scopable', 'choice', array(
            'choices' => array(
                0 => 'Global',
                1 =>'Channel'
            )
        ));
    }

}
