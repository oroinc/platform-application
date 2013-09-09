<?php

namespace Acme\Bundle\DemoWorkflowBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhoneCallType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'number',
                'text',
                array('required' => true)
            )
            ->add(
                'name',
                'text',
                array('required' => true)
            )
            ->add(
                'description',
                'textarea',
                array('required' => false)
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Acme\Bundle\DemoWorkflowBundle\Entity\PhoneCall',
                'intention'  => 'phonecall',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'acme_workflow_phonecall';
    }
}
