<?php
namespace Acme\Bundle\DemoDataFlowBundle\Configuration;

use JMS\Serializer\Annotation\Type;

/**
 * Job configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ImportCustomerConfiguration extends NewCsvConfiguration
{

    /**
     * @Type("string")
     * @var string
     */
    public $filePath;

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     * @return NewImportCustomerConfiguration
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

}
