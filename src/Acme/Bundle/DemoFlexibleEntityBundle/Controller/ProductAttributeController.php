<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Oro\Bundle\FlexibleEntityBundle\Entity\Attribute;
use Acme\Bundle\DemoFlexibleEntityBundle\Form\Type\ProductAttributeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Product attribute controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/productattribute")
 */
class ProductAttributeController extends Controller
{

    /**
     * Get product manager
     * @return FlexibleEntityManager
     */
    protected function getProductManager()
    {
        $pm = $this->container->get('product_manager');
        // force data locale if provided
        $dataLocale = $this->getRequest()->get('dataLocale');
        $pm->setLocale($dataLocale);
        // force data scope if provided
        $dataScope = $this->getRequest()->get('dataScope');
        $dataScope = ($dataScope) ? $dataScope : 'ecommerce';
        $pm->setScope($dataScope);

        return $pm;
    }

    /**
     * List product attributes
     * @Route("/index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $attributes = $this->getProductManager()->getAttributeRepository()
            ->findBy(array('entityType' => $this->getProductManager()->getEntityName()));

        return array('attributes' => $attributes);
    }

    /**
     * Create attribute
     *
     * @Route("/create")
     * @Template("AcmeDemoFlexibleEntityBundle:ProductAttribute:edit.html.twig")
     */
    public function createAction()
    {
        $attribute = $this->getProductManager()->createAttribute();

        return $this->editAction($attribute);
    }

    /**
     * Edit attribute form
     *
     * @Route("/edit/{id}", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template
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

                return $this->redirect($this->generateUrl('acme_demoflexibleentity_productattribute_edit', array('id' => $entity->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Remove attribute
     *
     * @Route("/remove/{id}", requirements={"id"="\d+"})
     */
    public function removeAction(Attribute $entity)
    {
        $em = $this->getProductManager()->getStorageManager();
        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Attribute successfully removed');

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_productattribute_index'));
    }

}
