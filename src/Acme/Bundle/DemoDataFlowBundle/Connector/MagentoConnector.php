<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector;

use Oro\Bundle\DataFlowBundle\Connector\AbstractConnector;
use Acme\Bundle\DemoDataFlowBundle\Form\Type\MagentoConnectorType;
use Acme\Bundle\DemoDataFlowBundle\Form\Handler\MagentoConnectorHandler;

/**
 * Magento connector
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MagentoConnector extends AbstractConnector
{

    /**
     * @var ConnectorRegistry
     */
    protected $registry;

    /**
     * @var string
     */
    protected $formId;

    /**
     * @var string
     */
    protected $formHandlerId;

    /**
     * Constructor
     * @param ConnectorRegistry $registry the form service id
     * @param string $form                the form service id
     * @param string $formHandler         the form handler service id
     */
    public function __construct(/*$registry,*/ $form, $formHandler)
    {
//        $this->registry     = $registry;
        $this->formId        = $form;
        $this->formHandlerId = $formHandler;
    }

    /**
     * Get configuration
     * @return \Acme\Bundle\DemoDataFlowBundle\Configuration\NewMagentoConfiguration
     */
    public function getNewConfigurationInstance()
    {
        // TODO : inject existing ?
        return new \Acme\Bundle\DemoDataFlowBundle\Configuration\NewMagentoConfiguration();
    }

    /**
     * Get configurations
     * @return \ArrayAccess
     */
    public function getConfigurations()
    {
        $typeConfiguration = 'Acme\Bundle\DemoDataFlowBundle\Configuration\NewMagentoConfiguration';

        return $this->registry->getConfigurations($typeConfiguration);
    }

    /**
     * Get jobs
     * @return \ArrayAccess
     */
    public function getJobs()
    {
        // TODO
    }

    /**
     * Get form
     * @return string
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * Get form handler
     * @return string
     */
    public function getFormHandlerId()
    {
        return $this->formHandlerId;
    }

}
