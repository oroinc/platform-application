<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector\Job;

use Acme\Bundle\DemoDataFlowBundle\Transform\CustomerTransformer;

use Ddeboer\DataImport\Source\Stream;

use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;

use Ddeboer\DataImport\Reader\CsvReader;

use Oro\Bundle\DataFlowBundle\Connector\Job\JobInterface;

/**
 * Import customers from Magento database
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ImportCustomersJob implements JobInterface
{
    /**
     * @var string
     */
    protected $code;

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
        $this->manager       = $manager;
        $this->code          = 'import_customer';
    }

    /**
     * set a flexible manager
     * @param FlexibleManager $manager
     */
    public function setManager(FlexibleManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get job code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Process
     *
     * @return multitype
     */
    public function process()
    {
        $messages = array();

        // Call reader
        $stream = new Stream('/tmp/export_customers.csv');
        $csvReader = new CsvReader($stream->getFile(), ',');
        $csvReader->setHeaderRowNumber(0);

        // Call transformer
        $transformer = new CustomerTransformer($this->manager);
        $customers = array();
        foreach ($csvReader as $customerItem) {
            $customers[] = $transformer->transform($customerItem);
        }

        // TODO : Call loader to persist customers

        return $customers;
    }

}
