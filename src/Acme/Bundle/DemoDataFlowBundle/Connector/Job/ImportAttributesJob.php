<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector\Job;

use Oro\Bundle\DataFlowBundle\Connector\Job\JobInterface;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Ddeboer\DataImport\Reader\DbalReader;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\Bundle\DemoDataFlowBundle\Transform\MagentoAttributeToOroAttribute;
use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;

/**
 * Import attributes from Magento database
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ImportAttributesJob implements JobInterface
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
    public function __construct(FlexibleManager $manager, $configuration)
    {
        $this->manager       = $manager;
        $this->configuration = $configuration;
        $this->code          = 'import_attributes';
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

        // prepare connection
        $params = $this->configuration['database'];
        $connection = DriverManager::getConnection($params, new Configuration());

        // query on magento attributes
        $prefix = $this->configuration['table_prefix'];
        $sql = 'SELECT * FROM '.$prefix.'eav_attribute AS att '
            .'INNER JOIN '.$prefix.'eav_entity_type AS typ ON att.entity_type_id = typ.entity_type_id AND typ.entity_type_code = "catalog_product"';
        $magentoReader = new DbalReader($connection, $sql);

        // query on oro attributes
        $codeToAttribute = $this->manager->getFlexibleRepository()->getCodeToAttributes(array());

        // read all attribute items
        $toExcludeCode = array('sku', 'old_id');
        $transformer = new MagentoAttributeToOroAttribute($this->manager);
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

}
