<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Oro\Bundle\FlexibleEntityBundle\Entity\Attribute;
use Acme\Bundle\DemoFlexibleEntityBundle\Form\Type\CustomerAttributeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Customer attribute controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/customerattribute")
 */
class CustomerAttributeController extends Controller
{

    /**
     * Get customer manager
     * @return Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleEntityManager
     */
    protected function getCustomerManager()
    {
        return $this->container->get('customer_manager');
    }

    /**
     * @Route("/index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        // TODO : should avoid to explicitely filter on entity type ?
        $attributes = $this->getCustomerManager()->getAttributeRepository()
            ->findBy(array('entityType' => $this->getCustomerManager()->getEntityName()));

        return array('attributes' => $attributes);
    }

    /**
     * Create attribute
     *
     * @Route("/create")
     * @Template("AcmeDemoFlexibleEntityBundle:CustomerAttribute:edit.html.twig")
     */
    public function createAction()
    {
        $attribute = $this->getCustomerManager()->createAttribute();

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
        $attClassName = $this->getCustomerManager()->getAttributeName();
        $form = $this->createForm(new CustomerAttributeType($attClassName), $entity);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getCustomerManager()->getStorageManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Attribute successfully saved');

                return $this->redirect($this->generateUrl('acme_demoflexibleentity_customerattribute_edit', array('id' => $entity->getId())));
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
        $em = $this->getCustomerManager()->getStorageManager();
        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Attribute successfully removed');

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_customerattribute_index'));
    }

}
