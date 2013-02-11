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
     * @param FlexibleManager $manager
     */
    public function __construct(FlexibleManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Process
     *
     * @return multitype
     */
    public function run()
    {
        $messages = array();

        // prepare connection
        $connection = DriverManager::getConnection($this->getConfiguration()->getDatabaseParameters(), new DbalConfiguration());

        // query on magento attributes
        $prefix = $this->getConfiguration()->getTablePrefix();
        $sql = 'SELECT * FROM '.$prefix.'eav_attribute AS att '
            .'INNER JOIN '.$prefix.'eav_entity_type AS typ '
            .'ON att.entity_type_id = typ.entity_type_id AND typ.entity_type_code = "catalog_product"';
        $magentoReader = new DbalReader($connection, $sql);

        // query on oro attributes
        $codeToAttribute = $this->manager->getFlexibleRepository()->getCodeToAttributes(array());
        // read all attribute items
        $toExcludeCode = explode(',', $this->getConfiguration()->getExcludedAttributes());
        $transformer = new AttributeTransformer($this->manager);
        foreach ($magentoReader as $attributeItem) {
            $attributeCode = $attributeItem['attribute_code'];
            // filter existing (just create new one)
            if (isset($codeToAttribute[$attributeCode])) {
                $messages[]= array('notice', $attributeCode.' already exists <br/>');
                continue;
            }
            // exclude from explicit list
            if (in_array($attributeCode, $toExcludeCode)) {
                $messages[]= array('notice', $attributeCode.' is in to exclude list <br/>');
                continue;
            }
            // persist new attributes
            $attribute = $transformer->reverseTransform($attributeItem);
            $this->manager->getStorageManager()->persist($attribute);
            $messages[]= array('success', $attributeCode.' inserted <br/>');
        }
        $this->manager->getStorageManager()->flush();

        return $messages;
    }


    /**
     * Get configuration
     * @return \Acme\Bundle\DemoDataFlowBundle\Configuration\MagentoConfiguration
     */
    public function getNewConfigurationInstance()
    {
        // TODO : inject existing ?
        return new \Acme\Bundle\DemoDataFlowBundle\Configuration\ImportAttributeConfiguration();
    }


    /**
     * Get form
     * @return string
     */
    public function getFormId()
    {
        return "configuration.form.import_attribute";
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
