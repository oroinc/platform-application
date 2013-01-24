<?php

namespace Acme\Bundle\DemoMeasureBundle\Controller;

use Acme\Bundle\DemoMeasureBundle\Measure\CapacitanceMeasure;
use Acme\Bundle\DemoMeasureBundle\Measure\MyLengthMeasure;

use Oro\Bundle\MeasureBundle\Convert\MeasureConverter;

use Oro\Bundle\MeasureBundle\Measure\LengthMeasure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Measure controller
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/measure")
 */
class MeasureController extends Controller
{
    /**
     * Example to call service
     *
     * @Route("/index")
     * @Template()
     *
     * @return multitype
     */
    public function indexAction()
    {
        $converter = $this->getMeasureConverter();

        $converter->setFamily(LengthMeasure::FAMILY);
        $result = $converter->convert(LengthMeasure::KILOMETER, LengthMeasure::MILE, 1);

        return array('result' => $result);
    }

    /**
     * Example using a custom measure (capacitance)
     *
     * @Route("/customMeasure")
     * @Template()
     *
     * @return multitype
     */
    public function customMeasureAction()
    {
        $converter = $this->getMeasureConverter();

        $converter->setFamily(CapacitanceMeasure::FAMILY);
        $result = $converter->convert(CapacitanceMeasure::FARAD, CapacitanceMeasure::KILOFARAD, 1500);

        return array('result' => $result);
    }

    /**
     * Example overriding Length measure to add a new custom unit (dong)
     *
     * @Route("/addedUnit")
     * @Template()
     *
     * @return multitype
     */
    public function addedUnitAction()
    {
        $converter = $this->getMeasureConverter();

        $converter->setFamily(LengthMeasure::FAMILY);
        $result = $converter->convert(LengthMeasure::KILOMETER, MyLengthMeasure::DONG, 1);

        return array('result' => $result);
    }

    /**
     * Dump configuration
     *
     * @Route("/dumpConfig")
     * @Template()
     *
     * @return multitype
     */
    public function dumpConfigAction()
    {
        $config = $this->container->getParameter('oro_measure.measures_config');

        return array('config' => $config['measures_config']);
    }

    /**
     * Get measure converter
     * @return MeasureConverter
     */
    protected function getMeasureConverter()
    {
        return $this->container->get('oro_measure.measure_converter');
    }
}
