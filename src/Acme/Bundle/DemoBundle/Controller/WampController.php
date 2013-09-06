<?php

namespace Acme\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/wamp")
 */
class WampController extends Controller
{
    /**
     * @Route("/", name="acme_demo_wamp_index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'maintenance' => $this->get('oro_platform.maintenance')->isOn()
        );
    }

    /**
     * @Route("/broadcast", name="acme_demo_wamp_broadcast")
     */
    public function broadcastAction()
    {
        $this->get('oro_wamp.publisher')->send(
            'acme/server-channel',
            array('msg' => 'Server generated event')
        );

        return $this->redirect($this->generateUrl('acme_demo_wamp_index'));
    }

    /**
     * @Route("/maintenance/{mode}", name="acme_demo_wamp_maintenance", requirements={"mode"="on|off"})
     */
    public function maintenanceAction($mode = 'on')
    {
        if ('on' == $mode) {
            $this->get('oro_platform.maintenance')->on();
            echo 'on';
        } else {
            $this->get('oro_platform.maintenance')->off();
        }

        return $this->redirect($this->generateUrl('acme_demo_wamp_index'));
    }
}
