<?php
namespace Acme\Bundle\DemoDataFlowBundle\Configuration;

use Oro\Bundle\DataFlowBundle\Configuration\ConfigurationInterface;
use JMS\Serializer\Annotation\Type;

/**
 * Connector configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class NewCsvConfiguration implements ConfigurationInterface
{

    /**
     * @Type("string")
     * @var string
     */
    public $charset = 'UFT-8';

    /**
     * @Type("string")
     * @var string
     */
    public $delimiter = ';';

    /**
     * @Type("string")
     * @var string
     */
    public $enclosure = '"';

    /**
     * @Type("string")
     * @var string
     */
    public $escape = '\\';

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     * @return CsvConfiguration
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     * @return CsvConfiguration
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param string $enclosure
     * @return CsvConfiguration
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * @return string
     */
    public function getEscape()
    {
        return $this->escape;
    }

    /**
     * @param string $escape
     * @return CsvConfiguration
     */
    public function setEscape($escape)
    {
        $this->escape = $escape;

        return $this;
    }

}
