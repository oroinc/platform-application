<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default flexible entity demo controller
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @Route("/flexible")
 */
class FlexibleController extends Controller
{
    /**
     * @Route("/index")
     * @Template()
     *
     * @return multitype
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Print config
     * @Route("/entityconfig")
     * @Template()
     *
     * @return multitype
     */
    public function entityConfigAction()
    {
        return array('config' => $this->container->getParameter('oro_flexibleentity.entities_config'));
    }
}
