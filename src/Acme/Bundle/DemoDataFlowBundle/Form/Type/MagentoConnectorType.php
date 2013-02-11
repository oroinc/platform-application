<?php
namespace Acme\Bundle\DemoDataFlowBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Configuration form type
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MagentoConnectorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('driver', 'text', array('required' => true));
        $builder->add('host', 'text', array('required' => true));
        $builder->add('port', 'text', array('required' => false));
        $builder->add('dbname', 'text', array('required' => true));
        $builder->add('user', 'text', array('required' => true));
        $builder->add('password', 'password', array('required' => true));
        $builder->add('charset', 'text', array('required' => true));
        $builder->add('prefix', 'text', array('required' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Acme\Bundle\DemoDataFlowBundle\Configuration\NewMagentoConfiguration'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'connector_magento_catalog';
    }
}