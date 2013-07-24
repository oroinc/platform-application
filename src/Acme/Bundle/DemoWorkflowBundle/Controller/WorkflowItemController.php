<?php

namespace Acme\Bundle\DemoWorkflowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\ORM\EntityManager;

use Oro\Bundle\WorkflowBundle\Exception\WorkflowNotFoundException;
use Oro\Bundle\WorkflowBundle\Model\WorkflowRegistry;
use Oro\Bundle\WorkflowBundle\Model\Workflow;

/**
 * @Route("/workflow_item", name="acme_demoworkflow_workflowitem_create")
 * @Template()
 */
class WorkflowItemController extends Controller
{
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

        return array(
            'workflowItem' => $workflowItem,
            'workflow' => $workflow,
            'currentStep' => $workflow->getStartStep()
        );
    }

    /**
     * Get Workflow by name
     *
     * @param string $name
     * @return Workflow
     * @throws NotFoundHttpException
     */
    protected function getWorkflow($name)
    {
        /** @var WorkflowRegistry $workflowRegistry */
        $workflowRegistry = $this->get('oro_workflow.registry');
        try {
            $workflow = $workflowRegistry->getWorkflow($name);
        } catch (WorkflowNotFoundException $e) {
            throw new NotFoundHttpException(sprintf('Workflow "%s" not found', $name));
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
            throw new NotFoundHttpException(
                sprintf(
                    'Entity of workflow "%s" with id=%s not found',
                    $workflow->getName(),
                    $workflow->getManagedEntityClass()
                )
            );
        }
        return $entity;
    }
}
