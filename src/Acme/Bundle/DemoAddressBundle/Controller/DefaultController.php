<?php

namespace Acme\Bundle\DemoAddressBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use YsTools\BackUrlBundle\Annotation\BackUrl;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\AddressBundle\Entity\Address;

class DefaultController extends Controller
{

    /**
     * Create address form
     * @Template("AcmeDemoAddressBundle:Default:edit.html.twig")
     */
    public function createAction()
    {
        /** @var  $addressManager \Oro\Bundle\AddressBundle\Entity\Manager\AddressManager */
        $addressManager = $this->get('oro_address.address.provider')->getStorage();

        $address = $addressManager->createFlexible();

        return $this->editAction($address);
    }

    /**
     * Edit address form
     *
     * @Template()
     * @BackUrl("back")
     */
    public function editAction(Address $entity)
    {
        if ($this->get('oro_address.form.handler.address')->process($entity)) {
            $backUrl = $this->getRedirectUrl($this->generateUrl('acme_demo_address_edit', array('id' => $entity->getId())));

            $this->getFlashBag()->add('success', 'Address successfully saved');
            return $this->redirect($backUrl);
        }

        return array(
            'form' => $this->get('oro_address.form.address')->createView(),
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
