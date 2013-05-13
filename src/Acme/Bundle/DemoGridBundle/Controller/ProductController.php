<?php
namespace Acme\Bundle\DemoGridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Oro\Bundle\GridBundle\Datagrid\ORM\QueryFactory\QueryFactory;
use Oro\Bundle\NavigationBundle\Annotation\TitleTemplate;

use Acme\Bundle\DemoGridBundle\Datagrid\ProductDatagridManager;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/list.{_format}",
     *      name="acme_demo_gridbundle_product_list",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     * @TitleTemplate("Products grid")
     */
    public function listAction(Request $request)
    {
        /** @var $productGridManager ProductDatagridManager */
        $productGridManager = $this->get('acme_demo_grid.product_grid.manager');
        $productGridManager->setEntityManager($this->getDoctrine()->getManager());
        $datagrid = $productGridManager->getDatagrid();

        if ('json' == $request->getRequestFormat()) {
            $view = 'OroGridBundle:Datagrid:list.json.php';
        } else {
            $view = 'AcmeDemoGridBundle:Product:list.html.twig';
        }

        return $this->render(
            $view,
            array('datagrid' => $datagrid->createView())
        );
    }
}
