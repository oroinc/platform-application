<?php
namespace Acme\Bundle\DemoMeasureBundle\Family;

/**
 * Capacitance measures constants
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
interface CapacitanceFamilyInterface
{

    /**
     * Family measure name
     * @staticvar string
     */
    const FAMILY = 'Capacitance';

    /**
     * @staticvar string
     */
    const FARAD     = 'FARAD';

    /**
     * @staticvar string
     */
    const KILOFARAD = 'KILOFARAD';

    /**
     * @staticvar string
     */
    const MEGAFARAD = 'MEGAFARAD';
}
