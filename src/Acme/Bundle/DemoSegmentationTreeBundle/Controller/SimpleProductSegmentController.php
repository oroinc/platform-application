<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\Controller;

use Acme\Bundle\DemoSegmentationTreeBundle\Helper\JsonItemsHelper;
use Oro\Bundle\SegmentationTreeBundle\Helper\JsonSegmentHelper;

use Oro\Bundle\SegmentationTreeBundle\Controller\BaseSegmentController;
use Oro\Bundle\SegmentationTreeBundle\Model\AbstractSegment;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Simple product Segment controller
 *
 * @author Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/simple-product-segment")
 *
 */
class SimpleProductSegmentController extends BaseSegmentController
{
    /**
     * {@inheritdoc}
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToIndex()
    {
        return $this->redirect($this->generateUrl('demo_segmentationtree_simpleproductsegment_index'));
    }

    /**
     * {@inheritdoc}
     *
     *
     * @ Route("/index")
     * @ Template()
    public function indexAction()
    {
        return $this->render('AcmeDemoSegmentationTreeBundle:SimpleProductSegment:index.html.twig');
    }
     */

    /**
     * Get the Segment manager associated with this controller
     * 
     * @return SegmentManager
     */
    protected function getSegmentManager()
    {
        return $this->get('acme_demo_segmentation_tree.simpleproduct_segment_manager');
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareItemListResponse(AbstractSegment $segment)
    {
        $simpleProducts = new ArrayCollection();

        if (is_object($segment)) {
            $simpleProducts = $segment->getSimpleProducts();
        }

        return JsonItemsHelper::simpleProductsResponse($simpleProducts);
    }


    /**
     * {@inheritdoc}
     *
     * @Method("POST")
     * @Route("/add-item")
     */
    public function addItemAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $segmentId = $request->get('segment_id');
            $productId = $request->get('item_id');
            
            $repo = $this->getSegmentManager()->getEntityRepository();
            $segment = $repo->find($segmentId);
            $product = $this->getDoctrine()->getManager()
                ->find('AcmeDemoSegmentationTreeBundle:SimpleProduct',$productId);

            $segment->addProduct($product);
            $this->getSegmentManager()->getStorageManager()->persist($segment);
            $this->getSegmentManager()->getStorageManager()->flush();

            $response = JsonSegmentHelper::statusOKResponse();

            return $this->prepareJsonResponse($response);
        } else {
            return $this->redirectToIndex();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @Method("POST")
     * @Route("/remove-item")
     */
    public function removeItemAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $segmentId = $request->get('segment_id');
            $productId = $request->get('item_id');
            
            $repo = $this->getSegmentManager()->getEntityRepository();
            $segment = $repo->find($segmentId);
            $product = $this->getDoctrine()->getManager()
                ->find('AcmeDemoSegmentationTreeBundle:SimpleProduct',$productId);

            $segment->removeProduct($product);
            $this->getSegmentManager()->getStorageManager()->persist($segment);
            $this->getSegmentManager()->getStorageManager()->flush();

            $response = JsonSegmentHelper::statusOKResponse();

            return $this->prepareJsonResponse($response);
        } else {
            return $this->redirectToIndex();
        }
    }

    
}
