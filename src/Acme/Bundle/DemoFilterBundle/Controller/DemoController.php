<?php
namespace Acme\Bundle\DemoFilterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @Route("/demo")
 */
class DemoController extends Controller
{
    /**
     * @Route("/frontend", name="acme_demo_filterbundle_demo_frontend")
     * @Template
     */
    public function frontendAction()
    {
        /** @var $formFactory FormFactoryInterface */
        $formFactory = $this->get('form.factory');
        $formBuilder = $formFactory->createNamedBuilder(
            'filter',
            'form',
            array(),
            array('csrf_protection' => false)
        );
        $formBuilder
            ->add(
                'text_filter',
                'oro_type_text_filter',
                array(
                    'label'       => 'Text Filter',
                    'show_filter' => true
                )
            )
            ->add(
                'number_filter',
                'oro_type_number_filter',
                array(
                    'label'       => 'Number Filter',
                    'show_filter' => true
                )
            )
            ->add(
                'date_filter',
                'oro_type_date_range_filter',
                array(
                    'label'       => 'Date Filter',
                    'show_filter' => true
                )
            )
            ->add(
                'datetime_filter',
                'oro_type_datetime_range_filter',
                array(
                    'label'       => 'DateTime Filter',
                    'show_filter' => true
                )
            )
            ->add(
                'boolean_filter',
                'oro_type_boolean_filter',
                array(
                    'label' => 'Boolean Filter',
                    'show_filter' => true
                )
            )
            ->add(
                'select_filter',
                'oro_type_choice_filter',
                array(
                    'label' => 'Select Filter',
                    'field_options' => array(
                        'multiple'  => false,
                        'choices'   => array(
                            1 => 'first select choice',
                            2 => 'second select choice',
                            3 => 'third select choice',
                        )
                    )
                )
            )
            ->add(
                'multiselect_filter',
                'oro_type_choice_filter',
                array(
                    'label' => 'Multiselect Filter',
                    'field_options' => array(
                        'multiple'  => true,
                        'choices'   => array(
                            1 => 'first multiselect choice',
                            2 => 'second multiselect choice',
                            3 => 'third multiselect choice',
                        )
                    )
                )
            );

        return array(
            'form' => $formBuilder->getForm()->createView()
        );
    }
}
