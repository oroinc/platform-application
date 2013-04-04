<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Oro\Bundle\FlexibleEntityBundle\Entity\Attribute;
use Acme\Bundle\DemoFlexibleEntityBundle\Form\Type\ProductAttributeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Product attribute controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/productattribute")
 */
class ProductAttributeController extends Controller
{

    /**
     * Get product manager
     * @return FlexibleManager
     */
    protected function getProductManager()
    {
        $pm = $this->container->get('product_manager');
        // force data locale if provided
        $dataLocale = $this->getRequest()->get('dataLocale');
        $pm->setLocale($dataLocale);
        // force data scope if provided
        $dataScope = $this->getRequest()->get('dataScope');
        $dataScope = ($dataScope) ? $dataScope : 'ecommerce';
        $pm->setScope($dataScope);

        return $pm;
    }

    /**
     * @Route("/index.{_format}",
     *      name="acme_demoflexibleentity_productattribute_index",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     */
    public function indexAction(Request $request)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder
            ->select('a.id', 'a.code', 'a.attributeType')
            ->from('OroFlexibleEntityBundle:Attribute', 'a')
            ->where("a.entityType = 'Acme\Bundle\DemoFlexibleEntityBundle\Entity\Product'");

        /** @var $queryFactory QueryFactory */
        $queryFactory = $this->get('productattribute_grid_manager.default_query_factory');
        $queryFactory->setQueryBuilder($queryBuilder);

        /** @var $gridManager AttributeDatagridManager */
        $gridManager = $this->get('productattribute_grid_manager');
        $datagrid = $gridManager->getDatagrid();

        if ('json' == $request->getRequestFormat()) {
            $view = 'OroGridBundle:Datagrid:list.json.php';
        } else {
            $view = 'AcmeDemoFlexibleEntityBundle:ProductAttribute:index.html.twig';
        }

        return $this->render(
            $view,
            array(
                'datagrid' => $datagrid,
                'form'     => $datagrid->getForm()->createView()
            )
        );
    }

    /**
     * Create attribute
     *
     * @Route("/create")
     * @Template("AcmeDemoFlexibleEntityBundle:ProductAttribute:edit.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $attribute = $this->getProductManager()->createAttribute();

        return $this->editAction($attribute);
    }

    /**
     * Edit attribute form
     *
     * @param Attribute $entity
     *
     * @Route("/edit/{id}", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template
     *
     * @return array
     */
    public function editAction(Attribute $entity)
    {
        $request = $this->getRequest();

        // create form
        $attClassName = $this->getProductManager()->getAttributeName();
        $form = $this->createForm(new ProductAttributeType($attClassName));
        $form->setData($entity);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getProductManager()->getStorageManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Attribute successfully saved');

                return $this->redirect(
                    $this->generateUrl(
                        'acme_demoflexibleentity_productattribute_edit',
                        array('id' => $entity->getId())
                    )
                );
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Remove attribute
     *
     * @param Attribute $entity
     *
     * @Route("/remove/{id}", requirements={"id"="\d+"})
     *
     * @return array
     */
    public function removeAction(Attribute $entity)
    {
        $em = $this->getProductManager()->getStorageManager();
        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Attribute successfully removed');

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_productattribute_index'));
    }
}
