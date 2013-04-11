<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Oro\Bundle\FlexibleEntityBundle\Entity\Attribute;
use Acme\Bundle\DemoFlexibleEntityBundle\Form\Type\CustomerAttributeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Customer attribute controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/customerattribute")
 */
class CustomerAttributeController extends Controller
{

    /**
     * Get customer manager
     * @return Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager
     */
    protected function getCustomerManager()
    {
        return $this->container->get('customer_manager');
    }

    /**
     * @Route("/index.{_format}",
     *      name="acme_demoflexibleentity_customerattribute_index",
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
            ->where("a.entityType = 'Acme\Bundle\DemoFlexibleEntityBundle\Entity\Customer'");

        /** @var $queryFactory QueryFactory */
        $queryFactory = $this->get('customerattribute_grid_manager.default_query_factory');
        $queryFactory->setQueryBuilder($queryBuilder);

        /** @var $gridManager AttributeDatagridManager */
        $gridManager = $this->get('customerattribute_grid_manager');
        $datagrid = $gridManager->getDatagrid();

        if ('json' == $request->getRequestFormat()) {
            $view = 'OroGridBundle:Datagrid:list.json.php';
        } else {
            $view = 'AcmeDemoFlexibleEntityBundle:CustomerAttribute:index.html.twig';
        }

        return $this->render($view, array('datagrid' => $datagrid));
    }

    /**
     * Create attribute
     *
     * @Route("/create")
     * @Template("AcmeDemoFlexibleEntityBundle:CustomerAttribute:edit.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $attribute = $this->getCustomerManager()->createAttribute();

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
        $attClassName = $this->getCustomerManager()->getAttributeName();
        $form = $this->createForm(new CustomerAttributeType($attClassName));
        $form->setData($entity);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getCustomerManager()->getStorageManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Attribute successfully saved');

                return $this->redirect(
                    $this->generateUrl(
                        'acme_demoflexibleentity_customerattribute_edit',
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
        $em = $this->getCustomerManager()->getStorageManager();
        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Attribute successfully removed');

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_customerattribute_index'));
    }
}
