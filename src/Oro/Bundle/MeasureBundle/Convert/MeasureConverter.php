<?php
namespace Oro\Bundle\MeasureBundle\Convert;

use Oro\Bundle\MeasureBundle\Exception\UnknownFamilyMeasureException;

use Oro\Bundle\MeasureBundle\Exception\UnknownMeasureException;

use Oro\Bundle\MeasureBundle\Exception\UnknownOperatorException;

/**
 * Aims to convert measures
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MeasureConverter
{

    /**
     * @var multitype
     */
    protected $config;

    /**
     * @var string
     */
    protected $family;

    /**
     * Constructor
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->config = $config;
    }

    /**
     * Set a family for the converter
     * @param string $family
     *
     * @return MeasureConverter
     *
     * @throws UUnknownFamilyMeasureException
     */
    public function setFamily($family)
    {
        if (!isset($this->config[$family])) {
            throw new UnknownFamilyMeasureException();
        }

        $this->family = $family;

        return $this;
    }

    /**
     * Convert a value from a base measure to a final measure
     * @param string $baseUnit  Base unit for value
     * @param string $finalUnit Result unit for value
     * @param double $value     Value to convert
     *
     * @return double
     */
    public function convert($baseUnit, $finalUnit, $value)
    {
        $standardValue = $this->convertBaseToStandard($baseUnit, $value);

        $result = $this->convertStandardToResult($finalUnit, $standardValue);

        return $result;
    }

    /**
     * Convert a value in a base unit to the standard unit
     * @param string $baseUnit Base unit for value
     * @param double $value    Value to convert
     *
     * @return float
     *
     * @throws UnknownOperatorException
     * @throws UnknownMeasureException
     */
    protected function convertBaseToStandard($baseUnit, $value)
    {
        if (!isset($this->config[$this->family]['units'][$baseUnit])) {
            throw new UnknownMeasureException();
        }
        $conversionConfig = $this->config[$this->family]['units'][$baseUnit]['convert'];
        $convertedValue = $value;

        // calculate result with conversion config
        foreach ($conversionConfig as $operator => $operand) {
            switch ($operator) {
                case "/":
                    if ($operand !== 0) {
                        $convertedValue = $convertedValue / $operand;
                    }
                    break;
                case "*":
                    $convertedValue = $convertedValue * $operand;
                    break;
                case "+":
                    $convertedValue = $convertedValue + $operand;
                    break;
                case "-":
                    $convertedValue = $convertedValue - $operand;
                    break;
                default:
                    throw new UnknownOperatorException();
            }
        }

        return $convertedValue;
    }

    /**
     * Convert a value in a standard unit to a final unit
     * @param string $finalUnit Final unit for value
     * @param double $value     Value to convert
     *
     * @return double
     *
     * @throws UnknownOperatorException
     * @throws UnknownMeasureException
     */
    protected function convertStandardToResult($finalUnit, $value)
    {
        if (!isset($this->config[$this->family]['units'][$finalUnit])) {
            throw new UnknownMeasureException();
        }
        $conversionConfig = $this->config[$this->family]['units'][$finalUnit]['convert'];
        $convertedValue = $value;

        // calculate result with conversion config
        foreach ($conversionConfig as $operator => $operand) {
            switch ($operator) {
                case "/":
                    $convertedValue = $convertedValue * $operand;
                    break;
                case "*":
                    if ($operand !== 0) {
                        $convertedValue = $convertedValue / $operand;
                    }
                    break;
                case "+":
                    $convertedValue = $convertedValue - $operand;
                    break;
                case "-":
                    $convertedValue = $convertedValue + $operand;
                    break;
                default:
                    throw new UnknownOperatorException();
            }
        }

        return $convertedValue;
    }

}
