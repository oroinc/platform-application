<?php

namespace Acme\Bundle\DemoWorkflowBundle\Controller;

use Doctrine\ORM\EntityManager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Oro\Bundle\WorkflowBundle\Exception\WorkflowNotFoundException;
use Oro\Bundle\WorkflowBundle\Model\WorkflowRegistry;
use Oro\Bundle\WorkflowBundle\Model\Workflow;
use Oro\Bundle\WorkflowBundle\Model\Transition;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;

/**
 * @Route("/workflow_item")
 */
class WorkflowItemController extends Controller
{
    /**
     * @Route("/", name="acme_demoworkflow_workflowitem_list")
     * @Template()
     */
    public function listAction()
    {
        /** @var WorkflowItem[] $workflowItems */
        $workflowItems = $this->getEntityManager()->getRepository('OroWorkflowBundle:WorkflowItem')->findAll();

        $workflows = array();
        foreach ($workflowItems as $workflowItem) {
            $workflowName = $workflowItem->getWorkflowName();
            $workflows[$workflowName] = $this->getWorkflow($workflowName);
        }

        return array(
            'workflowItems' => $workflowItems,
            'workflows' => $workflows
        );
    }

    /**
     * @Route("/create/{workflowName}/{entityId}", name="acme_demoworkflow_workflowitem_create")
     * @Template()
     */
    public function createAction($workflowName, $entityId)
    {
        $workflow = $this->getWorkflow($workflowName);
        $entity = null;
        if ($workflow->getManagedEntityClass()) {
            $entity = $this->getWorkflowEntity($workflow, $entityId);
        }

        $workflowItem = $workflow->createWorkflowItem($entity);
        $em = $this->getEntityManager();

        $em->persist($workflowItem);
        $em->flush();

        return $this->redirect(
            $this->generateUrl(
                'acme_demoworkflow_workflowitem_edit',
                array('id' => $workflowItem->getId())
            )
        );
    }

    /**
     * @Route("/edit/{id}", name="acme_demoworkflow_workflowitem_edit")
     * @Template()
     */
    public function editAction(Request $request, WorkflowItem $workflowItem)
    {
        $workflow = $this->getWorkflow($workflowItem->getWorkflowName());
        $currentStep = $workflow->getStep($workflowItem->getCurrentStepName());
        $workflowData = $workflowItem->getData();
        $stepForm = $this->createForm('oro_workflow_step', $workflowData, array('step' => $currentStep));

        if ($request->isMethod('POST')) {
            $stepForm->submit($request);

            if ($stepForm->isValid()) {
                $workflowItem->setUpdated();
                $this->getEntityManager()->flush();

                $this->get('session')->getFlashBag()->add('success', 'Workflow item data successfully saved');
            }
        }

        return array(
            'workflow' => $workflow,
            'currentStep' => $currentStep,
            'stepForm' => $stepForm->createView(),
            'workflowItem' => $workflowItem,
        );
    }

    /**
     * @Route("/transit/{id}/{transitionName}", name="acme_demoworkflow_workflowitem_transit")
     * @Template()
     */
    public function transitAction(WorkflowItem $workflowItem, $transitionName)
    {
        $workflow = $this->getWorkflow($workflowItem->getWorkflowName());

        /** @var Transition $transition */
        $transition = $workflow->getTransitions()->get($transitionName);
        if (!$transition) {
            throw new HttpException(500, sprintf('Transition "%s" not found', $transitionName));
        }

        if ($workflow->isTransitionAllowed($workflowItem, $transition)) {
            $em = $this->getEntityManager();
            $em->beginTransaction();
            try {
                $workflow->transit($workflowItem, $transition);
                $workflowItem->setUpdated();
                $em->flush();
                $em->commit();
            } catch (\Exception $e) {
                $em->rollback();
                throw $e;
            }

            $this->get('session')->getFlashBag()->add(
                'success',
                sprintf(
                    'Transition "%s" successfully performed. Workflow "%s" is now in step "%s"',
                    $transition->getLabel(),
                    $workflow->getLabel(),
                    $transition->getLabel()
                )
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'error',
                sprintf('Transition "%s" is not allowed', $transition->getLabel())
            );
        }

        return $this->redirect(
            $this->generateUrl(
                'acme_demoworkflow_workflowitem_edit',
                array('id' => $workflowItem->getId())
            )
        );
    }

    /**
     * Get Workflow by name
     *
     * @param string $name
     * @return Workflow
     * @throws HttpException
     */
    protected function getWorkflow($name)
    {
        /** @var WorkflowRegistry $workflowRegistry */
        $workflowRegistry = $this->get('oro_workflow.registry');
        try {
            $workflow = $workflowRegistry->getWorkflow($name);
        } catch (WorkflowNotFoundException $e) {
            throw new HttpException(500, sprintf('Workflow "%s" not found', $name));
        }
        return $workflow;
    }

    /**
     * Get entity that related to workflow by id
     *
     * @param Workflow $workflow
     * @param mixed $entityId
     * @return mixed
     * @throws HttpException
     */
    protected function getWorkflowEntity(Workflow $workflow, $entityId)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManagerForClass($workflow->getManagedEntityClass());
        if (!$em) {
            throw new HttpException(
                500,
                sprintf(
                    'Workflow "%s" managed class "%s" is not managed entity',
                    $workflow->getName(),
                    $workflow->getManagedEntityClass()
                )
            );
        }
        $entity = $em->find($workflow->getManagedEntityClass(), $entityId);
        if (!$entity) {
            throw new HttpException(
                500,
                sprintf(
                    'Entity of workflow "%s" with id=%s not found',
                    $workflow->getName(),
                    $workflow->getManagedEntityClass()
                )
            );
        }
        return $entity;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManagerForClass('OroWorkflowBundle:WorkflowItem');
    }
}
