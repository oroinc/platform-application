<?php

namespace Acme\Bundle\DemoGridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Oro\Bundle\NavigationBundle\Annotation\TitleTemplate;

use Oro\Bundle\GridBundle\Datagrid\DatagridManagerInterface;

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
        return $this->getGridResponse($this->get('acme_demo_grid.report.manager'));
    }

    /**
     * @Route("/mage/state/list.{_format}",
     *      name="acme_demo_gridbundle_mage_report_state_list",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     * @TitleTemplate("Magento order state report sample")
     * @Template
     */
    public function mageStateListAction()
    {
        return $this->getGridResponse($this->get('acme_demo_grid.mage_report_state.manager'));
    }

    protected function getGridResponse(DatagridManagerInterface $gridManager)
    {
        $gridManager->setEntityManager($this->getDoctrine()->getManager());

        $grid     = $gridManager->getDatagrid();
        $gridView = $grid->createView();

        if ('json' == $this->getRequest()->getRequestFormat()) {
            return $this->get('oro_grid.renderer')->renderResultsJsonResponse($gridView);
        }

        return array('datagrid' => $gridView);
    }
}
