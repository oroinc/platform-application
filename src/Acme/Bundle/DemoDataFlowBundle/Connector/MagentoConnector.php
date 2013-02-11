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
     * @var string
     */
    protected $formId;

    /**
     * @var string
     */
    protected $formHandlerId;

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
     * Get form
     * @return string
     */
    public function getFormId()
    {
        return "connector.form.magento_catalog";
    }

    /**
     * Get form handler
     * @return string
     */
    public function getFormHandlerId()
    {
        return "oro_dataflow.form.handler.configuration";
    }

}
