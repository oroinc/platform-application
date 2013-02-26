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
        $name = null;
        if (is_file('/tmp/form_perst.txt')) {
            $name = file_get_contents('/tmp/form_perst.txt');
        }
        return array('name' => $name);
    }

    /**
     * @Route("/pinbar/state/save", name="acme_demo_pinbar_save_state")
     */
    public function saveStateAction()
    {
        $container = $this->getRequest()->get('id');
        $name = $this->getRequest()->get('name');
        file_put_contents('/tmp/state_' . $container . '.txt', $name);

        $response = new Response(json_encode(array('success' => true)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/pinbar/post", name="acme_demo_pinbar_post")
     */
    public function postAction()
    {
        $name = $this->getRequest()->get('name');
        file_put_contents('/tmp/form_perst.txt', $name);
        return $this->redirect($this->generateUrl('acme_demo_pinbar_index'));
    }
}
