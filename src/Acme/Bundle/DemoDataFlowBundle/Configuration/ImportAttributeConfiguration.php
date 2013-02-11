<?php
namespace Acme\Bundle\DemoDataFlowBundle\Configuration;

use Oro\Bundle\DataFlowBundle\Configuration\ConfigurationInterface;
use JMS\Serializer\Annotation\Type;

/**
 * Job configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ImportAttributeConfiguration implements ConfigurationInterface
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
     *
     * @return MagentoConfiguration
     */
    public function setExcludedAttributes($excludedAttributes)
    {
        $this->excludedAttributes = $excludedAttributes;

        return $this;
    }
}
