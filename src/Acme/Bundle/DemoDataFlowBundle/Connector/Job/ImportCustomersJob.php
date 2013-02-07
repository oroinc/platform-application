<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector\Job;

use Oro\Bundle\DataFlowBundle\Connector\Job\AbstractJob;
use Acme\Bundle\DemoDataFlowBundle\Transform\CustomerTransformer;
use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;

use Ddeboer\DataImport\Source\Stream;
use Ddeboer\DataImport\Reader\CsvReader;

/**
 * Import customers from Magento database
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ImportCustomersJob extends AbstractJob
{

    /**
     * @var FlexibleManager
     */
    protected $manager;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @param FlexibleManager $manager
     */
    public function __construct(FlexibleManager $manager)
    {
        $this->manager = $manager;
        $this->configuration = array();
    }

    /**
     * {@inheritDoc}
     */
    public function configure($parameters)
    {
        $configuration = new ImportCustomersConfiguration();
        $this->configuration = $configuration->process($parameters);
    }

    /**
     * Process
     *
     * @return multitype
     */
    public function run()
    {
        $messages = array();

        // Call reader
        $stream = new Stream($this->configuration['file_path']);
        $csvReader = new CsvReader($stream->getFile(), ',');
        $csvReader->setHeaderRowNumber(0);

        // Call transformer
        $transformer = new CustomerTransformer($this->manager);
        $customers = array();
        foreach ($csvReader as $customerItem) {
            $customer = $transformer->transform($customerItem);
            $customers[] = $customer;
            $this->manager->getStorageManager()->persist($customer);
        }

        // TODO : Call loader to persist customers
        $this->manager->getStorageManager()->flush();

        return $customers;
    }

}
