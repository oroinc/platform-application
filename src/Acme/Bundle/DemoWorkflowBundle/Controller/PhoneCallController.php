<?php

namespace Acme\Bundle\DemoWorkflowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Oro\Bundle\WorkflowBundle\Model\WorkflowRegistry;

use Acme\Bundle\DemoWorkflowBundle\Form\PhoneCallType;
use Acme\Bundle\DemoWorkflowBundle\Entity\PhoneCall;

/**
 * @Route("/phone_calls")
 */
class PhoneCallController extends Controller
{
    /**
     * @Route("/", name="acme_demoworkflow_phonecall_list")
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
     * @Route("/create", name="acme_demoworkflow_phonecall_create")
     * @Template("AcmeDemoWorkflowBundle:PhoneCall:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $phoneCall = new PhoneCall();

        return $this->editAction($request, $phoneCall);
    }

    /**
     * @Route("/edit/{id}", name="acme_demoworkflow_phonecall_edit", requirements={"id"="\d+"})
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
            return $this->redirect(
                $this->generateUrl('acme_demoworkflow_phonecall_view', array('id' => $phoneCall->getId()))
            );
        }

        return array(
            'phoneCall' => $phoneCall,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/view/{id}", name="acme_demoworkflow_phonecall_view", requirements={"id"="\d+"})
     * @Template("AcmeDemoWorkflowBundle:PhoneCall:view.html.twig")
     */
    public function viewAction(PhoneCall $phoneCall)
    {
        /** @var WorkflowRegistry $workflowRegistry */
        $workflowRegistry = $this->get('oro_workflow.registry');

        $applicableWorkflows = $workflowRegistry->getWorkflowsByEntity($phoneCall);

        return array(
            'applicableWorkflows' => $applicableWorkflows,
            'phoneCall' => $phoneCall
        );
    }
}
