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
     * @return SimpleEntityManager
     */
    protected function getCustomerManager()
    {
        return $this->container->get('customer_manager');
    }

    /**
     * @Route("/index")
     * @Template()
     *
     * @return multitype
     */
    public function indexAction()
    {
        $customers = $this->getCustomerManager()->getEntityRepository()->findByWithAttributes();

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

    /**
     * @param integer $id
     *
     * @Route("/view/{id}")
     * @Template()
     *
     * @return multitype
     */
    public function viewAction($id)
    {
        // with any values
        $customer = $this->getCustomerManager()->getEntityRepository()->findWithAttributes($id);

        return array('customer' => $customer);
    }

}
