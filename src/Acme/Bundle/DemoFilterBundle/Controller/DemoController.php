<?php
namespace Acme\Bundle\DemoFilterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/demo")
 */
class DemoController extends Controller
{
    /**
     * @Route("/frontend", name="acme_demo_filterbundle_demo_frontend")
     * @Template
     */
    public function frontendAction()
    {
        return array();
    }
}
