<?php
namespace Acme\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\FlexibleEntityBundle\Entity\Attribute;

use Acme\Bundle\DemoBundle\Form\ProductAttributeType;

/**
 * @Route("/search")
 */
class FlexibleProductDemoController extends Controller
{
    /**
     * Create attribute
     *
     * @Route("/create", name="acme_demo_create_attribute")
     * @Template()
     *
     * @return array
     */
    public function createAction()
    {
        $attribute = $this->getProductManager()->createAttribute();

        return $this->editAction($attribute);
    }

    /**
     * Edit attribute form
     *
     * @param Attribute $entity
     *
     * @Route("/edit/{id}", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template
     *
     * @return array
     */
    public function editAction(Attribute $entity)
    {
        $request = $this->getRequest();

        // create form
        $attClassName = $this->getProductManager()->getAttributeName();
        $form = $this->createForm(new ProductAttributeType($attClassName), $entity);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getProductManager()->getStorageManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Attribute successfully saved');

                return $this->redirect(
                    $this->generateUrl(
                        'acme_demo_search',
                        array('id' => $entity->getId())
                    )
                );
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Get demo product manager
     * @return \Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager
     */
    protected function getProductManager()
    {
        return $this->container->get('demo_product_manager');
    }
}
