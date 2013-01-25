<?php
namespace Acme\Bundle\DemoMeasureBundle\Family;

use Oro\Bundle\MeasureBundle\Family\LengthFamily;

/**
 * Override LengthFamily class to add Dong measure constant
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MyLengthFamily extends LengthFamily
{

    /**
     * @staticvar string
     */
    const DONG = 'DONG';

}