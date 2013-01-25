<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\FlexibleEntityBundle\Form\Type\AttributeType;

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
     * @return multitype
     */
    public function indexAction()
    {
        // TODO : should avoid to explicitely filter on entity type ?
        $attributes = $this->getCustomerManager()->getAttributeRepository()
            ->findBy(array('entityType' => $this->getCustomerManager()->getEntityName()));

        return array('attributes' => $attributes);
    }

    /**
     * Create attribute form
     * @param string $attribute
     *
     * @return Symfony\Component\Form\Form
     */
    protected function createAttributeForm($attribute)
    {
        // get classes fullname
        $attClassFullname = $this->getCustomerManager()->getAttributeName();

        // create form
        $form = $this->createForm(
            new AttributeType($attClassFullname),
            $attribute
        );

        return $form;
    }

    /**
     * Displays a form to create a new attribute
     *
     * @Method("GET")
     * @Route("/new")
     * @Template()
     *
     * @return multitype
     */
    public function newAction()
    {
        $attribute = $this->getCustomerManager()->createAttribute();
        $form = $this->createAttributeForm($attribute);

        // render form
        return array('entity' => $attribute, 'form' => $form->createView());
    }

    /**
     * Create a new attribute
     *
     * @param Request $request the request
     *
     * @Route("/create")
     * @Method("POST")
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $attribute = $this->getCustomerManager()->createAttribute();

        $form = $this->createAttributeForm($attribute);
        $form->bind($request);

        // validation
        if ($form->isValid()) {
            try {
                // persists object
                $manager = $this->getCustomerManager()->getStorageManager();
                $manager->persist($attribute);
                $manager->flush();

                $this->get('session')->setFlash('success', 'attribute %code% has been created');

                return $this->redirect(
                    $this->generateUrl('acme_demoflexibleentity_customerattribute_edit', array('id' => $attribute->getId()))
                );

            } catch (\Exception $e) {
                $this->get('session')->setFlash('error', $e->getMessage());
            }
        }

        // render form
        return $this->render(
            'AcmeDemoFlexibleEntityBundle:CustomerAttribute:new.html.twig',
            array(
                'entity' => $attribute,
                'form'   => $form->createView()
            )
        );
    }

    /**
     * Displays a form to edit an existing attribute
     *
     * @param integer $id Attribute id to edit
     *
     * @Method("GET")
     * @Route("/{id}/edit")
     * @Template()
     *
     * @return multitype
     */
    public function editAction($id)
    {
        $attribute = $this->getCustomerManager()->getAttributeRepository()->find($id);
        if (!$attribute) {
            $this->get('session')->setFlash('error', "Attribute {$id} not found");

            return $this->redirect(
                $this->generateUrl('acme_demoflexibleentity_customerattribute_index')
            );
        }
        $form = $this->createAttributeForm($attribute);

        // render form
        return array('entity' => $attribute, 'form' => $form->createView());
    }

    /**
     * Update an existing attribute
     * @param Request $request
     * @param integer $id
     *
     * @Method("POST")
     * @Route("/{id}/update")
     * @Template()
     *
     * @return multitype
     */
    public function updateAction(Request $request, $id)
    {
        $attribute = $this->getCustomerManager()->getAttributeRepository()->find($id);

        if (!$attribute) {
            $this->get('session')->setFlash('error', "Attribute {$id} not found");

            return $this->redirect(
                $this->generateUrl('acme_demoflexibleentity_customerattribute_index')
            );
        }

        $form = $this->createAttributeForm($attribute);
        $form->bind($request);

        if ($form->isValid()) {

            try {
                // persists object
                $manager = $this->getCustomerManager()->getStorageManager();
                $manager->persist($attribute);
                $manager->flush();

                $this->get('session')->setFlash('success', "Attribute {$id} has been updated");

                return $this->redirect(
                    $this->generateUrl('acme_demoflexibleentity_customerattribute_edit', array('id' => $id))
                );
            } catch (\Exception $e) {
                $this->get('session')->setFlash('error', $e->getMessage());
            }
        }

        // render form
        return $this->render(
            'AcmeDemoFlexibleEntityBundle:CustomerAttribute:edit.html.twig',
            array(
                'entity' => $attribute,
                'form' => $form->createView()
            )
        );
    }

    /**
     * Remove an existing attribute
     * @param integer  $id
     *
     * @Method("GET")
     * @Route("/{id}/delete")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        try {
            $attribute = $this->getCustomerManager()->getAttributeRepository()->find($id);
            if (!$attribute) {
                $this->get('session')->setFlash('error', "Attribute {$id} not found");

                return $this->redirect(
                    $this->generateUrl('acme_demoflexibleentity_customerattribute_index')
                );
            }

            // delete attribute
            $this->getCustomerManager()->getStorageManager()->remove($attribute);
            $this->getCustomerManager()->getStorageManager()->flush();
        } catch (\Exception $e) {
            $this->get('session')->setFlash('error', $e->getMessage());

            return $this->redirect(
                $this->generateUrl('acme_demoflexibleentity_customerattribute_index')
            );
        }

        $this->get('session')->setFlash('success', "Attribute {$id} has been successfully removed");

        return $this->redirect(
            $this->generateUrl('acme_demoflexibleentity_customerattribute_index')
        );
    }

}
