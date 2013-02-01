<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Form\Type;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\FlexibleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Customer form type
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CustomerType extends FlexibleType
{

    /**
     * {@inheritdoc}
     */
    public function addEntityFields(FormBuilderInterface $builder)
    {
        // add default flexible fields
        parent::addEntityFields($builder);
        // customer fields
        $builder->add('firstname', 'text');
        $builder->add('lastname', 'text');
        $builder->add('email', 'email', array('required' => true));
    }

}