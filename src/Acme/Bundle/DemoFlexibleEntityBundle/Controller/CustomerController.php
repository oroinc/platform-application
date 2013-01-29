<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleEntityManager;
use Acme\Bundle\DemoFlexibleEntityBundle\Form\Type\CustomerType;
use Acme\Bundle\DemoFlexibleEntityBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Customer entity controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/customer")
 */
class CustomerController extends Controller
{

    /**
     * Get customer manager
     * @return FlexibleEntityManager
     */
    protected function getCustomerManager()
    {
        return $this->container->get('customer_manager');
    }

    /**
     * @Route("/index")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        $customers = $this->getCustomerManager()->getEntityRepository()->findByWithAttributes();

        return array('customers' => $customers);
    }

    /**
     * Query customers
     *
     * @param string $attributes attribute codes
     * @param string $criteria   criterias
     * @param string $orderBy    order by
     * @param int    $limit      limit
     * @param int    $offset     offset
     *
     * @Route("/query/{attributes}/{criteria}/{orderBy}/{limit}/{offset}", defaults={"attributes" = null, "criteria" = null, "orderBy" = null, "limit" = null, "offset" = null})
     *
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return array
     */
    public function queryAction($attributes, $criteria, $orderBy, $limit, $offset)
    {
        // prepare params
        if (!is_null($attributes) and $attributes !== 'null') {
            $attributes = explode('&', $attributes);
        } else {
            $attributes = array();
        }
        if (!is_null($criteria) and $criteria !== 'null') {
            parse_str($criteria, $criteria);
        } else {
            $criteria = array();
        }
        if (!is_null($orderBy) and $orderBy !== 'null') {
            parse_str($orderBy, $orderBy);
        } else {
            $orderBy = array();
        }

        // get entities
        $customers = $this->getCustomerManager()->getEntityRepository()->findByWithAttributes(
            $attributes, $criteria, $orderBy, $limit, $offset
        );

        return array('customers' => $customers);
    }

    /**
     * @Route("/query-lazy-load")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return multitype
     */
    public function queryLazyLoadAction()
    {
        $customers = $this->getCustomerManager()->getEntityRepository()->findBy(array());

        return array('customers' => $customers);
    }

    /**
     * @param integer $id
     *
     * @Route("/show/{id}")
     * @Template()
     *
     * @return multitype
     */
    public function showAction($id)
    {
        // with any values
        $customer = $this->getCustomerManager()->getEntityRepository()->findWithAttributes($id);

        return array('customer' => $customer);
    }

    /**
     * Create customer
     *
     * @Route("/create")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:edit.html.twig")
     *
     * @return multitype
     */
    public function createAction()
    {
        $entity = $this->getCustomerManager()->createEntity(FlexibleEntityManager::ALL_VALUES);

        return $this->editAction($entity);
    }

    /**
     * Edit customer
     *
     * @param Customer $entity
     *
     * @Route("/edit/{id}", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template
     *
     * @return multitype
     */
    public function editAction(Customer $entity)
    {
        $request = $this->getRequest();

        // create form
        $entClassName = $this->getCustomerManager()->getEntityName();
        $valueClassName = $this->getCustomerManager()->getEntityValueName();
        $form = $this->createForm(new CustomerType($entClassName, $valueClassName), $entity);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getCustomerManager()->getStorageManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Customer successfully saved');

                return $this->redirect($this->generateUrl('acme_demoflexibleentity_customer_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Remove customer
     * @param Customer $entity
     *
     * @Route("/remove/{id}", requirements={"id"="\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Customer $entity)
    {
        $em = $this->getCustomerManager()->getStorageManager();
        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Customer successfully removed');

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_customer_index'));
    }

}
