<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Manufacturer controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/manufacturer")
 */
class ManufacturerController extends Controller
{

    /**
     * Get manager
     *
     * @return SimpleEntityManager
     */
    protected function getManufacturerManager()
    {
        return $this->container->get('manufacturer_manager');
    }

    /**
     * @Route("/index")
     * @Template()
     *
     * @return multitype
     */
    public function indexAction()
    {
        $manufacturers = $this->getManufacturerManager()->getEntityRepository()->findAll();

        return array('manufacturers' => $manufacturers);
    }

}
