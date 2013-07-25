<?php

namespace Acme\Bundle\DemoGridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Oro\Bundle\NavigationBundle\Annotation\TitleTemplate;

/**
 * @Route("/report")
 */
class ReportController extends Controller
{
    /**
     * @Route("/list.{_format}",
     *      name="acme_demo_gridbundle_report_list",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     * @TitleTemplate("Report sample")
     * @Template
     */
    public function listAction()
    {
        $gridManager = $this->get('acme_demo_grid.report_grid.manager');

        $gridManager->setEntityManager($this->getDoctrine()->getManager());

        $grid     = $gridManager->getDatagrid();
        $gridView = $grid->createView();

        if ('json' == $this->getRequest()->getRequestFormat()) {
            return $this->get('oro_grid.renderer')->renderResultsJsonResponse($gridView);
        }

        return array('datagrid' => $gridView);
    }
}