<?php

namespace Acme\Bundle\DemoMenuBundle\Controller;

use Oro\Bundle\UserBundle\Acl\Manager;
use Symfony\Component\Routing\Router;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\UserBundle\Annotation\Acl;

/**
 * Menu Demo controller
 *
 * @Route("/menu")
 */
class MenuController extends Controller
{
    /**
     * Example of menu
     *
     * @Route("/index", name="oro_menu_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Menu bundle tests.
     *
     * @Route("/test", name="oro_menu_test")
     * @Template
     */
    public function testAction()
    {
        return array();
    }

    /**
     * @Route("/submenu", name="oro_menu_submenu")
     * @Acl(
     *      id = "oro_menu_controller_submenu",
     *      name="Oro menu test controller submenu",
     *      description = "Test controller for submenu"
     * )
     * @Template
     */
    public function submenuAction()
    {
        return array();
    }
}
