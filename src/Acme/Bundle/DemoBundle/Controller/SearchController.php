<?php

namespace Acme\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Acme\Bundle\DemoBundle\Entity\Product;
use Acme\Bundle\DemoBundle\Form\ProductType;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * List of products and add new product
     *
     * @Route("/", name="acme_demo_search")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $em      = $this->getDoctrine()->getManager();
        $product = new Product();
        $form    = $this->createForm(new ProductType(), $product);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em->persist($product);
                $em->flush();
            }
        }

        return array(
            'products' => $em->getRepository('AcmeDemoBundle:Product')->findAll(),
            'form'     => $form->createView(),
        );
    }

    /**
     * Edit product
     *
     * @Route("/edit/{id}", name="acme_demo_edit")
     * @Template()
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction($id)
    {
        $request = $this->getRequest();
        $em      = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('AcmeDemoBundle:Product')->find($id);
        $form    = $this->createForm(new ProductType(), $product);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em->persist($product);
                $em->flush();

                return $this->redirect($this->generateUrl('acme_demo_search'));
            }
        }

        return array(
            'product' => $product,
            'form'    => $form->createView(),
        );
    }

    /**
     * Delete product
     *
     * @Route("/delete/{id}", name="acme_demo_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em      = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('AcmeDemoBundle:Product')->find($id);

        $em->remove($product);
        $em->flush();

        return $this->redirect($this->generateUrl('acme_demo_search'));
    }

    /**
     * @Template()
     * @return array
     */
    public function testAction()
    {
        return array();
    }

    /**
     * @Route("/query", name="acme_demo_query")
     * @Template()
     * @return array
     */
    public function queryAction()
    {
        return array();
    }

    /**
     * Get search service manager (wheel implement in controllers parent class)
     *
     * @return \Oro\Bundle\SearchBundle\Engine\Indexer
     */
    private function getSearchManager()
    {
        return $this->get('oro_search.index');
    }
}
