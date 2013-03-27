<?php

namespace Acme\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Acme\Bundle\DemoBundle\Entity\Product;
use Acme\Bundle\DemoBundle\Form\ProductType;

use Acme\Bundle\DemoBundle\Entity\Customer;
use Acme\Bundle\DemoBundle\Form\CustomerType;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Route("/product/{id}", name="acme_demo_search_product")
     * @Template()
     */
    public function productPageAction($id)
    {
        return array(
            'product' => $this->getDoctrine()->getRepository('AcmeDemoBundle:Product')->find($id)
        );
    }

    /**
     * @Route("/advanced-search-page", name="acme_demo_advanced_search")
     * @Template()
     * @return array
     */
    public function advancedSearchAction()
    {
        return array();
    }
}
