<?php

namespace Acme\Bundle\DemoAddressBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use YsTools\BackUrlBundle\Annotation\BackUrl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Acme\Bundle\DemoAddressBundle\Entity\SeparateAddress;

class SeparateController extends Controller
{

    /**
     * Create address form
     * @Template("AcmeDemoAddressBundle:Separate:edit.html.twig")
     */
    public function createAction()
    {
        /** @var  $addressManager \Oro\Bundle\AddressBundle\Entity\Manager\AddressManager */
        $addressManager = $this->get('oro_address.address.provider')->getStorage('service');

        $address = $addressManager->createFlexible();

        return $this->editAction($address);
    }

    /**
     * Edit address form
     *
     * @Template()
     * @BackUrl("back")
     */
    public function editAction(SeparateAddress $entity)
    {
        if ($this->get('oro_address.form.handler.address.service')->process($entity)) {
            $backUrl = $this->getRedirectUrl($this->generateUrl('acme_demo_service_address_edit', array('id' => $entity->getId())));

            $this->getFlashBag()->add('success', 'Address successfully saved');
            return $this->redirect($backUrl);
        }

        return array(
            'form' => $this->get('oro_address.service.form.address')->createView(),
        );
    }

    /**
     * Get redirect URLs
     *
     * @param  string $default
     * @return string
     */
    protected function getRedirectUrl($default)
    {
        $flashBag = $this->getFlashBag();
        if ($this->getRequest()->query->has('back')) {
            $backUrl = $this->getRequest()->get('back');
            $flashBag->set('backUrl', $backUrl);
        } elseif ($flashBag->has('backUrl')) {
            $backUrl = $flashBag->get('backUrl');
            $backUrl = reset($backUrl);
        } else {
            $backUrl = null;
        }

        return $backUrl ? $backUrl : $default;
    }

    /**
     * @return FlashBag
     */
    protected function getFlashBag()
    {
        return $this->get('session')->getFlashBag();
    }
}
