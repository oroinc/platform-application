<?php

namespace Acme\Bundle\DemoMenuBundle\Controller;

use Oro\Bundle\UserBundle\Acl\Manager;
use Symfony\Component\Routing\Router;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\Acl;

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
     * Acl(
     *      id = "oro_menu_controller_submenu",
     *      label="Oro menu test controller submenu",
     *      type = "action",
     *      group_name=""
     * )
     * @Template
     */
    public function submenuAction()
    {
        return array();
    }

    /**
     * @Route("/form", name="oro_menu_form")
     * @Template
     */
    public function formAction()
    {
        return array('name' => $this->getRequest()->get('name'));
    }
}
