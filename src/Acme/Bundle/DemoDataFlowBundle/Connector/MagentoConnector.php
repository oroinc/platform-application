<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector;

use Oro\Bundle\DataFlowBundle\Connector\AbstractConnector;
use Acme\Bundle\DemoDataFlowBundle\Form\Type\MagentoConnectorType;
use Acme\Bundle\DemoDataFlowBundle\Form\Handler\MagentoConnectorHandler;
use Oro\Bundle\DataFlowBundle\Configuration\EditableConfigurationInterface;

/**
 * Magento connector
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MagentoConnector extends AbstractConnector implements EditableConfigurationInterface
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
     * Get form
     * @return string
     */
    public function getConfigurationFormServiceId()
    {
        return "configuration.form.magento_catalog";
    }

    /**
     * Get form handler
     * @return string
     */
    public function getConfigurationFormHandlerServiceId()
    {
        return "oro_dataflow.form.handler.configuration";
    }
}
