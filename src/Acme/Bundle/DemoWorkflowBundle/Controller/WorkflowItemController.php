<?php

namespace Acme\Bundle\DemoWorkflowBundle\Controller;

use Acme\Bundle\DemoWorkflowBundle\Entity\PhoneCall;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\ORM\EntityManager;

use Acme\Bundle\DemoWorkflowBundle\Stubs\WorkflowRegistry;

class WorkflowItemController extends Controller
{
    /**
     * @Route("/index", name="acme_demo_workflowbundle_workflowitem_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/create_workflow_item", name="acme_demo_workflowbundle_workflowitem_show")
     * @Template()
     */
    public function showAction()
    {
        $workflowItem = $this->getWorkflowItem();

        if (!$workflowItem) {
            /** @var WorkflowRegistry $registry */
            $registry = $this->get('oro_workflow.registry');
            $workflow = $registry->getWorkflow('phone_call_workflow');
            $workflowItem = $workflow->createWorkflowItem();
            $this->getEntityManager()->persist($workflowItem);
            $this->getEntityManager()->flush();
        }

        return array(
            'workflowItem' => $workflowItem
        );
    }

    /**
     * @Route("/update_workflow_item", name="acme_demo_workflowbundle_workflowitem_update")
     * @Template()
     */
    public function updateWorkflowDataAction()
    {
        $this->getEntityManager()->beginTransaction();


        $workflowItem = $this->getWorkflowItem();
        $workflowItem->setUpdated();

        $workflowItem->getData()->set('test', 'Random value ' . rand(1, 1000));

        $phoneCall = new PhoneCall();
        $phoneCall->setName('John Doe');
        $phoneCall->setDescription('A call to John Doe');
        $phoneCall->setNumber('0 800 455 66 889');
        $phoneCall->setSuccessful(true);
        $phoneCall->setTimeout(50);

        $this->getEntityManager()->persist($phoneCall);
        $this->getEntityManager()->flush($phoneCall);

        $workflowItem->getData()->set('phoneCall', $phoneCall);

        $this->getEntityManager()->flush();

        $this->getEntityManager()->commit();

        return $this->redirect($this->generateUrl('acme_demo_workflowbundle_workflowitem_show'));
    }

    /**
     * @Route("/clear_workflow_item", name="acme_demo_workflowbundle_workflowitem_clear")
     */
    public function clearWorkflowItemsAction()
    {
        $workflowItems = $this->getWorkflowItemRepository()->findAll();

        foreach ($workflowItems as $workflowItem) {
            $this->getEntityManager()->remove($workflowItem);
        }

        $this->getEntityManager()->flush();

        return $this->redirect($this->generateUrl('acme_demo_workflowbundle_workflowitem_show'));
    }

    /**
     * @return WorkflowItem
     */
    protected function getWorkflowItem()
    {
        return $this->getWorkflowItemRepository()->findOneBy(
            array(
                'workflowName' => 'phone_call_workflow'
            )
        );
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getWorkflowItemRepository()
    {
        return $this->getEntityManager()->getRepository('OroWorkflowBundle:WorkflowItem');
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
