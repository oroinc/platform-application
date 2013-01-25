<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

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
     * @return SimpleEntityManager
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
                    $this->generateUrl('acme_demoflexibleentity_customerattribute_new', array('id' => $attribute->getId()))
                );

            } catch (\Exception $e) {
                $this->get('session')->setFlash('error', $e->getMessage());
            }
        }

        // render form
        return $this->render(
            'DemoFlexibleEntityBundle:CustomerAttribute:new.html.twig',
            array(
                'entity' => $attribute,
                'form'   => $form->createView()
            )
        );
    }

}
