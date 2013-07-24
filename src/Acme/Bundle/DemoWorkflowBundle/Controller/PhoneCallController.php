<?php

namespace Acme\Bundle\DemoWorkflowBundle\Controller;

use Acme\Bundle\DemoWorkflowBundle\Form\PhoneCallType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Acme\Bundle\DemoWorkflowBundle\Entity\PhoneCall;

/**
 * @Route("/phone_calls")
 */
class PhoneCallController extends Controller
{
    /**
     * @Route("/", name="acme_demoworkflow_phone_call_list")
     * @Template()
     */
    public function listAction()
    {
        $phoneCalls = $this->getDoctrine()->getRepository('AcmeDemoWorkflowBundle:PhoneCall')->findAll();
        return array(
            'phoneCalls' => $phoneCalls
        );
    }

    /**
     * @Route("/create", name="acme_demoworkflow_phone_call_create")
     * @Template("AcmeDemoWorkflowBundle:PhoneCall:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $phoneCall = new PhoneCall();

        return $this->editAction($request, $phoneCall);
    }

    /**
     * @Route("/edit/{id}", name="acme_demoworkflow_phone_call_edit", requirements={"id"="\d+"})
     * @Template("AcmeDemoWorkflowBundle:PhoneCall:edit.html.twig")
     */
    public function editAction(Request $request, PhoneCall $phoneCall)
    {
        $form = $this->createForm(new PhoneCallType(), $phoneCall);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManagerForClass('AcmeDemoWorkflowBundle:PhoneCall');
            $em->persist($phoneCall);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Phone call successfully saved');
            return $this->redirect($this->generateUrl('acme_demo_workflowbundle_phone_call_list'));
        }

        return array(
            'phoneCall' => $phoneCall,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/view/{id}", name="acme_demoworkflow_phone_call_view", requirements={"id"="\d+"})
     * @Template("AcmeDemoWorkflowBundle:PhoneCall:view.html.twig")
     */
    public function viewAction(PhoneCall $phoneCall)
    {
        // @TODO Get workflows that are applicable to PhoneCall


        return array(
            'phoneCall' => $phoneCall
        );
    }

    protected function getWorkflowItemsByPhoneCall(PhoneCall $phoneCall)
    {
        return array();
    }
}
