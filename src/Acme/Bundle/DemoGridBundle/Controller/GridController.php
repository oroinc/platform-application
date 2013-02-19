<?php
namespace Acme\Bundle\DemoGridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormBuilderInterface;
use Sonata\DoctrineORMAdminBundle\Datagrid\Pager as SonataDoctrineORMPager;
use Oro\Bundle\GridBundle\Datagrid\Datagrid;
use Oro\Bundle\GridBundle\Datagrid\ORM\QueryFactory\QueryFactory;
use Oro\Bundle\GridBundle\Field\FieldDescriptionCollection;
use Oro\Bundle\GridBundle\Field\FieldDescription;

use Acme\Bundle\DemoGridBundle\Datagrid\UserDatagridManager;

/**
 * @Route("/grid")
 */
class GridController extends Controller
{
    /**
     * @Route("/list", name="acme_demo_gridbundle_grid_list")
     * @Template()
     */
    public function listAction()
    {
        /** @var $userGridManager UserDatagridManager */
        $userGridManager = $this->get('acme_demo_grid.user_grid.manager');
        $datagrid = $userGridManager->getDatagrid();

        return array(
            'datagrid'  => $datagrid,
            'form'      => $datagrid->getForm()->createView(),
            'routeName' => 'acme_demo_gridbundle_grid_list',
        );
    }
}
