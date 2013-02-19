<?php
namespace Acme\Bundle\DemoDataFlowBundle\Job;

use Oro\Bundle\DataFlowBundle\Job\AbstractJob;
use Doctrine\DBAL\Configuration as DbalConfiguration;
use Doctrine\DBAL\DriverManager;
use Ddeboer\DataImport\Reader\DbalReader;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\Bundle\DemoDataFlowBundle\Transform\AttributeTransformer;
use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;

/**
 * Import attributes from Magento database
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ImportAttributesJob extends AbstractJob
{

    /**
     * @var FlexibleManager
     */
    protected $manager;

    /**
     * @var DbalReader
     */
    protected $dbalReader;

    /**
     * @var \ArrayAccess
     */
    protected $attributes;

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
        // prepare connection
        $this->messages = array();
        $dbalParams = $this->getConnectorConfiguration()->getDbalParameters();
        $connection = DriverManager::getConnection($dbalParams, new DbalConfiguration());

        // read data from Magento
        $prefix = $this->getConnectorConfiguration()->getTablePrefix();
        $sql = 'SELECT * FROM '.$prefix.'eav_attribute AS att '
            .'INNER JOIN '.$prefix.'eav_entity_type AS typ '
            .'ON att.entity_type_id = typ.entity_type_id AND typ.entity_type_code = "catalog_product"';
        $this->dbalReader = new DbalReader($connection, $sql);
    }

    /**
     * {@inheritDoc}
     */
    protected function transform()
    {
        $codeToAttribute = $this->manager->getFlexibleRepository()->getCodeToAttributes(array());
        $toExcludeCode = explode(',', $this->getConfiguration()->getExcludedAttributes());
        $transformer = new AttributeTransformer($this->manager);
        $this->attributes = array();
        foreach ($this->dbalReader as $attributeItem) {
            $attributeCode = $attributeItem['attribute_code'];
            // filter existing (just create new one)
            if (isset($codeToAttribute[$attributeCode])) {
                $this->messages[]= array('notice', $attributeCode.' already exists <br/>');
                continue;
            }
            // exclude from explicit list
            if (in_array($attributeCode, $toExcludeCode)) {
                $this->messages[]= array('notice', $attributeCode.' is in to exclude list <br/>');
                continue;
            }
            // attributes to save
            $this->attributes[] = $transformer->reverseTransform($attributeItem);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function load()
    {
        foreach ($this->attributes as $attribute) {
            $this->manager->getStorageManager()->persist($attribute);
            $this->messages[]= array('success', $attribute->getCode().' inserted <br/>');
        }
        $this->manager->getStorageManager()->flush();
    }
}
