<?php
namespace Acme\Bundle\DemoGridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormBuilderInterface;
use Sonata\DoctrineORMAdminBundle\Datagrid\Pager as SonataDoctrineORMPager;
use Oro\Bundle\GridBundle\Datagrid\Datagrid;
use Oro\Bundle\GridBundle\Datagrid\ORM\QueryFactory;
use Oro\Bundle\GridBundle\Field\FieldDescriptionCollection;
use Oro\Bundle\GridBundle\Field\FieldDescription;

/**
 * @Route("/grid")
 */
class GridController extends Controller
{
    /**
     * @Route("/list")
     * @Template()
     */
    public function listAction()
    {
        $doctrineManager = $this->getDoctrine();
        $className = 'Acme\\Bundle\\DemoFlexibleEntityBundle\\Entity\\Customer';
        $queryFactory = new QueryFactory($doctrineManager, $className);

        $fieldDescriptions = new FieldDescriptionCollection();
        $fieldDescriptions->add(new FieldDescription());
        $pager = new SonataDoctrineORMPager(10);

        $formData = array();
        $formOptions = array();
        /** @var $formBuilder FormBuilderInterface */
        $formBuilder = $this->container->get('form.factory')->createBuilder('form', $formData, $formOptions);

        $datagrid = new Datagrid(
            $queryFactory->createQuery(),
            $fieldDescriptions,
            $pager,
            $formBuilder
        );

        return array(
            'datagrid' => $datagrid
        );
    }
}
