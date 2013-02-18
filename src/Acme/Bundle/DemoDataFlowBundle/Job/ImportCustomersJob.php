<?php
namespace Acme\Bundle\DemoDataFlowBundle\Job;

use Oro\Bundle\DataFlowBundle\Job\AbstractJob;
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
     *@var CsvReader
     */
    protected $reader;

    /**
     *@var /ArrayAccess
     */
    protected $customers;

    /**
     * Constructor
     * @param string          $confConnectorName the configuration FQCN
     * @param string          $confJobName       the configuration FQCN
     * @param FlexibleManager $manager           the customer manager
     */
    public function __construct($confConnectorName, $confJobName, FlexibleManager $manager)
    {
        parent::__construct($confConnectorName, $confJobName);
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     */
    protected function extract()
    {
        $this->messages = array();
        $stream = new Stream($this->getConfiguration()->getFilePath());
        $this->csvReader = new CsvReader(
            $stream->getFile(),
            $this->getConnectorConfiguration()->getDelimiter(),
            $this->getConnectorConfiguration()->getEnclosure(),
            $this->getConnectorConfiguration()->getEscape()
        );
        $this->csvReader->setHeaderRowNumber(0);
    }

    /**
     * {@inheritDoc}
     */
    protected function transform()
    {
        $transformer = new CustomerTransformer($this->manager);
        $this->customers = array();
        foreach ($this->csvReader as $customerItem) {
            $this->customers[] = $transformer->transform($customerItem);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function load()
    {
        foreach ($this->customers as $customer) {
            $this->manager->getStorageManager()->persist($customer);
            $this->messages[]= array('success', $customer->getEmail().' inserted <br/>');
        }
        $this->manager->getStorageManager()->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getFormTypeServiceId()
    {
        return "configuration_import_customer";
    }
}
