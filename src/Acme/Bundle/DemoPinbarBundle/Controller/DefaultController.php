<?php

namespace Acme\Bundle\DemoPinbarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/pinbar/index", name="acme_demo_pinbar_index")
     * @Template()
     */
    public function indexAction()
    {
        $items = array(
            array('title' => 'Item 1', 'route' => 'acme_demo_pinbar_index'),
            array('title' => 'Item 2', 'route' => 'acme_demo_pinbar_index'),
            array('title' => 'Item 3', 'route' => 'acme_demo_pinbar_index'),
            array('title' => 'Item 4', 'route' => 'acme_demo_pinbar_index'),
            array('title' => 'Item 5', 'route' => 'acme_demo_pinbar_index'),
            array('title' => 'Item 6', 'route' => 'acme_demo_pinbar_index'),
        );
        return array(
            'pinbar' => array(
                'maxPinbarItems' => 4,
                'items' => $items
            )
        );
    }

    /**
     * @Route("/pinbar/test", name="acme_demo_pinbar_test")
     */
    public function testAction()
    {
        $request = $this->getRequest()->__toString();
        file_put_contents('/tmp/request.txt', $request);

        $response = new Response(json_encode(array('success' => true)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
