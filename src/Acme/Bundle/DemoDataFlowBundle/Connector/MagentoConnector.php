<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector;

use Oro\Bundle\DataFlowBundle\Connector\AbstractConnector;
use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;
use Acme\Bundle\DemoDataFlowBundle\Job\ImportAttributes;

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
     * @var \ArrayAccess
     */
    protected $configuration;

    /**
     * @param FlexibleManager $manager
     */
    public function __construct(FlexibleManager $manager)
    {
        $this->manager = $manager;
        $this->configuration = array(
            'dbal' => array(
                    'driver'   => 'pdo_mysql',
                    'host'     => '127.0.0.1',
                    'dbname'   => 'magento',
                    'user'     => 'root',
                    'password' => 'root',
            ),
            'prefix' => ''
        );
        $this->jobs = array();
    }

}
