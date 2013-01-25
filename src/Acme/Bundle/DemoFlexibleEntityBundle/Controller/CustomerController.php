<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

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
