<?php

namespace Acme\Bundle\DemoFormBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class PersonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'acme_demo_form_person';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                'text',
                array(
                    'label' => 'Username',
                    'required' => true,
                )
            )
            ->add(
                'email',
                'email',
                array(
                    'label' => 'E-mail',
                    'required' => true,
                )
            )
            ->add(
                'plainPassword',
                'repeated',
                array(
                    'type' => 'text',
                    'required' => true,
                    'invalid_message' => 'The password fields must match.',
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Password again'),
                )
            )
            ->add(
                'dob',
                'oro_date',
                array(
                    'label' => 'Date of birth',
                    'required' => false,
                )
            )
            ->add(
                'firstName',
                'text',
                array(
                    'label' => 'First Name',
                    'required' => true,
                )
            )
            ->add(
                'lastName',
                'text',
                array(
                    'label' => 'Last Name',
                    'required' => true,
                )
            )
            ->add(
                'about',
                'textarea',
                array(
                    'label' => 'About',
                    'required' => true,
                )
            )
            ->add(
                'gender',
                'choice',
                array(
                    'choices' => array('Male' => 'Male', 'Female' => 'Female'),
                    'required' => true,
                )
            )
            ->add(
                'hobbies',
                'choice',
                array(
                    'required' => true,
                    'multiple' => true,
                    'choices' => array(
                        'Books' => 'Books',
                        'Films' => 'Films',
                        'Photography' => 'Photography',
                        'Sport' => 'Sport',
                        'Travel' => 'Travel'
                    )
                )
            )
            ->add(
                'subscriptions',
                'choice',
                array(
                    'required' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => array(
                        'Subscription #1' => 'Subscription #1',
                        'Subscription #2' => 'Subscription #2'
                    )
                )
            )
            ->add(
                'avatar',
                'file'
            )
            ->add(
                'acceptTerms',
                'choice',
                array(
                    'required' => true,
                    'multiple' => false,
                    'expanded' => true,
                    'label' => 'I accept terms',
                    'choices' => array(
                        0 => 'No, I don\'t accept terms and conditions',
                        1 => 'Yes, I accept terms and conditions',
                    )
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Acme\Bundle\DemoFormBundle\Form\Model\Person',
            )
        );
    }
}
