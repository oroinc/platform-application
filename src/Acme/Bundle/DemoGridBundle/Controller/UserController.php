<?php
namespace Acme\Bundle\DemoGridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Oro\Bundle\NavigationBundle\Annotation\TitleTemplate;

use Acme\Bundle\DemoGridBundle\Datagrid\UserDatagridManager;

/**
 * @Route("/users")
 */
class UserController extends Controller
{
    /**
     * @Route("/list.{_format}",
     *      name="acme_demo_gridbundle_user_list",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     * @TitleTemplate("Users grid")
     */
    public function listAction(Request $request)
    {
        /** @var $userGridManager UserDatagridManager */
        $userGridManager = $this->get('acme_demo_grid.user_grid.manager');
        $datagrid = $userGridManager->getDatagrid();

        if ('json' == $request->getRequestFormat()) {
            $view = 'OroGridBundle:Datagrid:list.json.php';
        } else {
            $view = 'AcmeDemoGridBundle:User:list.html.twig';
        }

        return $this->render($view, array('datagrid' => $datagrid));
    }
}
