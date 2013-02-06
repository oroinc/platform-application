<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector;

use Oro\Bundle\DataFlowBundle\Connector\AbstractConnector;
use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;
use Acme\Bundle\DemoDataFlowBundle\Connector\Job\ImportAttributesJob;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Job interface
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MagentoConnector extends AbstractConnector
{

    /**
     * @param FlexibleManager $manager
     */
    public function __construct(FlexibleManager $manager)
    {
        parent::__construct();
        $this->manager = $manager;
    }

    /**
     * Configure connector
     * TODO: put configuration in cache ?
     */
    public function configure()
    {
        // parse connector config
        $configs = Yaml::parse(__DIR__.'/../Resources/config/oro_connector.yml');

        // process configuration
        $configuration = new MagentoConfiguration($configs);
        $this->configuration = $configuration->process();

        //$this->addJob(new ImportAttributesJob($this->manager, $this->configuration));
    }

}
