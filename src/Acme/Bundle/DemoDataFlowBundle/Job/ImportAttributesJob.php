<?php
namespace Acme\Bundle\DemoDataFlowBundle\Job;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Oro\Bundle\DataFlowBundle\Job\JobInterface;
use Ddeboer\DataImport\Reader\DbalReader;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\Bundle\DemoDataFlowBundle\DataTransformer\MagentoAttributeToOroAttribute;
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
    public function __construct(FlexibleManager $manager)
    {
        $this->manager       = $manager;
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
        $this->code          = 'import_attribute';
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
     */
    public function process()
    {
        // prepare connection
        $params = $this->configuration['dbal'];
        $connection = DriverManager::getConnection($params, new Configuration());

        // query on lines
        $prefix = $this->configuration['prefix'];
        $sql = 'SELECT * FROM '.$prefix.'eav_attribute AS att '
            .'INNER JOIN '.$prefix.'eav_entity_type AS typ ON att.entity_type_id = typ.entity_type_id AND typ.entity_type_code = "catalog_product"';
        $reader = new DbalReader($connection, $sql);

        // read all items
        $transformer = new MagentoAttributeToOroAttribute($this->manager);
        foreach ($reader as $item) {

            $attribute = $transformer->reverseTransform($item);
            if ($attribute->getId() > 0) {
                echo 'already exists <br/>';
            } else {
                $this->manager->getStorageManager()->persist($attribute);
                echo $attribute->getCode().' inserted <br/>';
            }

            $this->manager->getStorageManager()->flush();
        }
    }


}
