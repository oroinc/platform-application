<?php
namespace Acme\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use Oro\Bundle\SecurityBundle\Annotation\Acl;

/**
 * @Route("/acl")
 */
class AclAccessController extends Controller
{
    /**
     * @Acl(
     *      id = "acme_demo_test_1",
     *      label ="demo1",
     *      type = "action",
     *      group_name=""
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function test1Action()
    {
        return  new Response('Action for ROLE_USER');
    }

    /**
     * @Acl(
     *      id = "acme_demo_test_2",
     *      label="demo2",
     *      type = "action",
     *      group_name=""
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function test2Action()
    {
        return  new Response('Action for ROLE_USER');
    }

    /**
     * @Acl(
     *      id = "acme_demo_test_3",
     *      label="demo3",
     *      type = "action",
     *      group_name=""
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function test3Action()
    {
        return  new Response('Action for ROLE_USER');
    }

    /**
     * @Acl(
     *      id = "acme_demo_test_controller_role_user",
     *      label="Action for ROLE_USER",
     *      type = "action",
     *      group_name=""
     * )
     * @Route("/users", name="acme_demo_acl_users_only")
     */
    public function usersEnabledAction()
    {
        return  new Response('Action for ROLE_USER');
    }

    /**
     * @Acl(
     *      id = "acme_demo_test_controller_role_manager",
     *      label="Action for ROLE_MANAGER",
     *      type = "action",
     *      group_name=""
     * )
     * @Route("/manager", name="acme_demo_acl_manager_only")
     */
    public function managerEnabledAction()
    {
        return  new Response('Action for ROLE_MANAGER');
    }

    /**
     * @Acl(
     *      id = "acme_demo_test_controller_role_admin",
     *      label="Action for ROLE_ADMIN",
     *      type = "action",
     *      group_name=""
     * )
     * @Route("/admin", name="acme_demo_acl_manager_only")
     */
    public function adminEnabledAction()
    {
        return  new Response('Action for ROLE_ADMIN');
    }

    /**
     * @Route("/wo-acl", name="acme_demo_acl_manager_only")
     */
    public function actionWOAclAction()
    {
        return  new Response('Action without ACL Resource');
    }
}
