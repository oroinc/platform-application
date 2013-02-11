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
class ImportAttributeConfiguration extends NewMagentoConfiguration
{

    /**
     * @Type("string")
     * @var string
     */
    public $excludedAttributes;

    /**
     * @return string
     */
    public function getExcludedAttributes()
    {
        return $this->excludedAttributes;
    }

    /**
     * @param string $excludedAttributes
     * @return NewMagentoConfiguration
     */
    public function setExcludedAttributes($excludedAttributes)
    {
        $this->excludedAttributes = $excludedAttributes;

        return $this;
    }

}
