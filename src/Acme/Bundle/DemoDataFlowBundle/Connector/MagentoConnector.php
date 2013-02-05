<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector;

use Oro\Bundle\DataFlowBundle\Connector\ConnectorInterface;
use Oro\Bundle\DataFlowBundle\Job\JobInterface;
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
class MagentoConnector implements ConnectorInterface
{

    /**
     * @var \ArrayAccess
     */
    protected $jobs;

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

    /**
     * Get jobs
     *
     * @return \ArrayAccess
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * Get job by code
     *
     * @param string $code
     *
     * @return JobInterface
     */
    public function getJob($code)
    {
        return $this->jobs[$code];
    }

    /**
     * Add a job
     * @param JobInterface $job
     */
    public function addJob(JobInterface $job)
    {
        $this->jobs[$job->getCode()]= $job;
    }

    /**
     * Process a job
     * @param string $code
     */
    public function process($code)
    {
        $job = $this->getJob($code);
        $job->process();
    }

}
