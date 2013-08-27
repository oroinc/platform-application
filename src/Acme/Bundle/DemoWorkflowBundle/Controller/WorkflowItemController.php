<?php

namespace Acme\Bundle\DemoWorkflowBundle\Controller;

use Doctrine\ORM\EntityManager;

use Oro\Bundle\WorkflowBundle\Exception\ForbiddenTransitionException;
use Oro\Bundle\WorkflowBundle\Exception\UnknownTransitionException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Oro\Bundle\WorkflowBundle\Exception\WorkflowNotFoundException;
use Oro\Bundle\WorkflowBundle\Model\WorkflowRegistry;
use Oro\Bundle\WorkflowBundle\Model\Workflow;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Model\WorkflowManager;

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
     * @Route(
     *      "/start/{workflowName}/{entityClass}/{entityId}/{transition}",
     *      name="acme_demoworkflow_workflowitem_start"
     * )
     */
    public function startAction($workflowName, $entityClass, $entityId, $transition)
    {
        // TODO: refactor and use JS widgets instead
        $response = $this->forward(
            'OroWorkflowBundle:Api/Rest/Workflow:start',
            array(
                'workflowName'   => $workflowName,
                'entityClass'    => $entityClass,
                'entityId'       => $entityId,
                'transitionName' => $transition,
                '_format'        => 'json'
            )
        );

        if ($response->getStatusCode() != 200) {
            return $response;
        }

        $responseData = json_decode($response->getContent(), true);
        if (empty($responseData['workflowItemId'])) {
            throw new \LogicException('There is no workflow item ID');
        }

        return $this->redirect(
            $this->generateUrl(
                'acme_demoworkflow_workflowitem_edit',
                array('id' => $responseData['workflowItemId'])
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
        $workflowData = $workflowItem->getData();
        $currentStep = $workflow->getStep($workflowItem->getCurrentStepName());
        if (!$currentStep) {
            throw new \LogicException(
                sprintf('There is no step "%s"', $workflowItem->getCurrentStepName())
            );
        }

        $stepForm = $this->createForm(
            $currentStep->getFormType(),
            $workflowData,
            array('workflow' => $workflow, 'step' => $currentStep)
        );

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
     */
    public function transitAction(WorkflowItem $workflowItem, $transitionName)
    {
        // TODO: refactor and use JS widgets instead
        $response = $this->forward(
            'OroWorkflowBundle:Api/Rest/Workflow:transit',
            array(
                'workflowItemId' => $workflowItem->getId(),
                'transitionName' => $transitionName,
                '_format'        => 'json'
            )
        );

        if ($response->getStatusCode() != 200) {
            return $response;
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
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManagerForClass('OroWorkflowBundle:WorkflowItem');
    }
}
