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
     * @Route("/query/{attributes}/{criteria}/{orderBy}/{limit}/{offset}", defaults={"attributes" = null, "criteria" = null, "orderBy" = null, "limit" = null, "offset" = null})
     *
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @param array      $attributes attribute codes
     * @param array      $criteria   criterias
     * @param array|null $orderBy    order by
     * @param int|null   $limit      limit
     * @param int|null   $offset     offset
     *
     * @return array
     */
    public function queryAction($attributes, $criteria, $orderBy, $limit, $offset)
    {
        $customers = $this->getCustomerManager()->getEntityRepository()->findByWithAttributes(
            $attributes, $criteria, $orderBy, $limit, $offset
        );

        return array('customers' => $customers);
    }


    /**
     * @Route("/querylazyload")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return multitype
     */
    public function querylazyloadAction()
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
     */
    public function createAction()
    {
        $entity = $this->getCustomerManager()->createEntity();

        return $this->editAction($entity);
    }

    /**
     * Edit customer
     *
     * @Route("/edit/{id}", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template
     */
    public function editAction(Customer $entity)
    {
        $request = $this->getRequest();

        // create form
        $entClassName = $this->getCustomerManager()->getEntityName();
        $form = $this->createForm(new CustomerType($entClassName), $entity);

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
     *
     * @Route("/remove/{id}", requirements={"id"="\d+"})
     */
    public function removeAction(Customer $entity)
    {
        $em = $this->getCustomerManager()->getStorageManager();
        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Customer successfully removed');

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_customer_index'));
    }




    /**
     * @Route("/queryonlydob")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return multitype
     */
    public function queryonlydobAction()
    {
        $customers = $this->getCustomerManager()->getEntityRepository()->findByWithAttributes(array('dob'));

        return array('customers' => $customers);
    }

    /**
     * @Route("/queryonlydobandgender")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return multitype
     */
    public function queryonlydobandgenderAction()
    {
        $customers = $this->getCustomerManager()->getEntityRepository()->findByWithAttributes(array('dob', 'gender'));

        return array('customers' => $customers);
    }

    /**
     * @Route("/queryfilterfirstname")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return multitype
     */
    public function queryfilterfirstnameAction()
    {
        $customers = $this->getCustomerManager()
                          ->getEntityRepository()
                          ->findByWithAttributes(
                              array(),
                              array('firstname' => 'Nicolas')
                          );

        return array('customers' => $customers);
    }

    /**
     * @Route("/queryfilterfirstnameandcompany")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return multitype
     */
    public function queryfilterfirstnameandcompanyAction()
    {
        $customers = $this->getCustomerManager()
                          ->getEntityRepository()
                          ->findByWithAttributes(
                              array('company', 'dob', 'gender'),
                              array('firstname' => 'Nicolas', 'company' => 'Akeneo')
                          );

        return array('customers' => $customers);
    }

    /**
     * @Route("/queryfilterfirstnameandlimit")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return multitype
     */
    public function queryfilterfirstnameandlimit()
    {
        // initialize vars
        $limit = 10;
        $start = 0;

        // get customers filtered by firstname = "Nicolas" and limited
        $customers = $this->getCustomerManager()
                          ->getEntityRepository()
                          ->findByWithAttributes(
                              array(),
                              array('firstname' => 'Nicolas'),
                              null,
                              $limit,
                              $start
                          );

        return array('customers' => $customers);
    }

    /**
     * @Route("/queryfilterfirstnameandorderbirthdatedesc")
     * @Template("AcmeDemoFlexibleEntityBundle:Customer:index.html.twig")
     *
     * @return multitype
     */
    public function queryfilterfirstnameandorderbirthdatedescAction()
    {
        $customers = $this->getCustomerManager()
                          ->getEntityRepository()
                          ->findByWithAttributes(
                              array('dob'),
                              array('firstname' => 'Nicolas'),
                              array('dob' => 'desc')
                          );

        return array('customers' => $customers);
    }


}
