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
        $formBuilder->add(
            'text_filter',
            'oro_type_text_filter',
            array('label' => 'Text Filter')
        );
        $formBuilder->add(
            'number_filter',
            'oro_type_number_filter',
            array('label' => 'Number Filter')
        );

        return array(
            'formView' => $formBuilder->getForm()->createView()
        );
    }
}
