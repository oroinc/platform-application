<?php
namespace Acme\Bundle\DemoWorkflowBundle\Stubs;

use Oro\Bundle\WorkflowBundle\Model\Step;
use Oro\Bundle\WorkflowBundle\Model\Attribute;
use Oro\Bundle\WorkflowBundle\Model\Workflow;
use Oro\Bundle\WorkflowBundle\Model\WorkflowRegistry as BaseWorkflowRegistry;

class WorkflowRegistry extends BaseWorkflowRegistry
{
    /**
     * @var Workflow
     */
    protected $workflow;

    /**
     * Get stub workflow
     *
     * @param string $name
     * @return Workflow
     */
    public function getWorkflow($name)
    {
        if (!$this->workflow) {
            $this->workflow = new Workflow();
            $this->workflow->setManagedEntityClass('Acme\Bundle\DemoWorkflowBundle\Entity\PhoneCall');
            $this->workflow->setName('phone_call_workflow');
            $this->workflow->setEnabled(true);

            $step = new Step();
            $step->setName('Initial Step');

            $this->workflow->getSteps()->add($step);
            $this->workflow->setStartStepName($step->getName());

            $phoneCallAttribute = new Attribute();
            $phoneCallAttribute->setName('phoneCall');
            $phoneCallAttribute->setOptions(
                array(
                    'entity_class' => 'Acme\Bundle\DemoWorkflowBundle\Entity\PhoneCall'
                )
            );
            $step->getAttributes()->set($phoneCallAttribute->getName(), $phoneCallAttribute);

            $conversationAttribute = new Attribute();
            $conversationAttribute->setName('conversation');
            $conversationAttribute->setOptions(
                array(
                    'entity_class' => 'Acme\Bundle\DemoWorkflowBundle\Entity\PhoneConversation'
                )
            );
            $step->getAttributes()->set($conversationAttribute->getName(), $conversationAttribute);

            $nameAttribute = new Attribute();
            $nameAttribute->setName('name');
            $nameAttribute->setFormTypeName('text');
            $step->getAttributes()->set($nameAttribute->getName(), $nameAttribute);

            $numberAttribute = new Attribute();
            $numberAttribute->setName('number');
            $numberAttribute->setFormTypeName('text');
            $step->getAttributes()->set($numberAttribute->getName(), $numberAttribute);
        }

        return $this->workflow;
    }
}
