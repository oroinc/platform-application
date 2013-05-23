<?php
namespace Acme\Bundle\DemoGridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Template("AcmeDemoGridBundle:User:list.html.twig")
     */
    public function listAction(Request $request)
    {
        /** @var $userGridManager UserDatagridManager */
        $userGridManager = $this->get('acme_demo_grid.user_grid.manager');
        $datagrid = $userGridManager->getDatagrid();
        $datagridView = $datagrid->createView();

        if ('json' == $request->getRequestFormat()) {
            return $this->get('oro_grid.renderer')->renderResultsJsonResponse($datagridView);
        }

        return array('datagrid' => $datagridView);
    }
}
