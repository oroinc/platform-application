<?php
namespace Acme\Bundle\DemoMeasureBundle\Measure;

use Oro\Bundle\MeasureBundle\Measure\LengthMeasure;

/**
 * Override LengthMeasure class to add Dong measure constant
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MyLengthMeasure extends LengthMeasure
{

    /**
     * @staticvar string
     */
    const DONG = 'DONG';

}