<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Form\Type;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\FlexibleValueType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Product value form type
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ProductValueType extends FlexibleValueType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'acme_product_value';
    }
}
