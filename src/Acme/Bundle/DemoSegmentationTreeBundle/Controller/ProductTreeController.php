<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\Controller;

use Acme\Bundle\DemoSegmentationTreeBundle\Helper\JsonSegmentHelper;

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
 * Controller to manage product segment nodes and segmentation tres
 *
 * @author    Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/product-tree")
 *
 */
class ProductTreeController extends Controller
{
    /**
     * Display index screen
     *
     * @return Response
     *
     * @Route("/index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Get the Segment manager associated with this controller
     *
     * @return SegmentManager
     */
    protected function getSegmentManager()
    {
        return $this->get('acme_demo_segmentation_tree.product_segment_manager');
    }

    /**
     * Send children segments linked to the one which id is provided
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Method("GET")
     * @Route("/children", name="acme_demotree_children")
     */
    public function childrenAction(Request $request)
    {
        $parentId = (int) $request->get('id');

        if ($parentId <= 0) {
            throw new \InvalidArgumentException("Missing segment id parameter 'id':".$parentId);
        }

        $segments = $this->getSegmentManager()->getChildren($parentId);

        $data = JsonSegmentHelper::childrenResponse($segments);

        return $this->prepareJsonResponse($data);
    }

    /**
     * Search for a segment by its title
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $search = $request->get('search_str');
        $treeRootId = $request->get('tree_root_id');

        $segments = $this->getSegmentManager()->search($treeRootId, array('title' => $search));

        $data = JsonSegmentHelper::searchResponse($segments);

        return $this->prepareJsonResponse($data);
    }

    /**
     * Create a new node
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Method("POST")
     * @Route("/create-node")
     * @Template()
     */
    public function createNodeAction(Request $request)
    {
        $parentId = $request->get('id');
        $title = $request->get('title');

        $segment = $this->getSegmentManager()->getSegmentInstance();

        $segment->setTitle($title);

        $repo = $this->getSegmentManager()->getEntityRepository();
        $parent = $repo->find($parentId);

        $segment->setParent($parent);

        $this->getSegmentManager()->getStorageManager()->persist($segment);
        $this->getSegmentManager()->getStorageManager()->flush();

        $data = JsonSegmentHelper::createNodeResponse(1, $segment->getId());

        return $this->prepareJsonResponse($data);
    }

    /**
     * Rename a node
     * @param Request $request
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/rename-node")
     * @Template()
     */
    public function renameNodeAction(Request $request)
    {
        $this->getSegmentManager()->rename($request->get('id'), $request->get('title'));
        $this->getSegmentManager()->getStorageManager()->flush();

        $data = JsonSegmentHelper::statusOKResponse();

        return $this->prepareJsonResponse($data);
    }

    /**
     * Remove a node
     * @param Request $request
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/remove-node")
     */
    public function removeNodeAction(Request $request)
    {
        $this->getSegmentManager()->removeById($request->get('id'));
        $this->getSegmentManager()->getStorageManager()->flush();

        $data = JsonSegmentHelper::statusOKResponse();

        return $this->prepareJsonResponse($data);
    }

    /**
     * Move a node
     * @param Request $request
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/move-node")
     */
    public function moveNodeAction(Request $request)
    {
        $segmentId = $request->get('id');
        $parentId = $request->get('parent');
        $prevSiblingId = $request->get('prev_sibling');


        if ($request->get('copy') == 1) {
            $this->getSegmentManager()->copy($segmentId, $parentId, $prevSiblingId);
        } else {
            $this->getSegmentManager()->move($segmentId, $parentId, $prevSiblingId);
        }

        $this->getSegmentManager()->getStorageManager()->flush();

        // format response to json content
        $data = JsonSegmentHelper::statusOKResponse();

        return $this->prepareJsonResponse($data);
    }

    /**
     * Send trees
     * @param Request $request
     *
     * @return Response
     *
     * @Method("GET")
     * @Route("/trees", name="acme_demotree_tree")
     */
    public function treesAction(Request $request)
    {
        $trees = $this->getSegmentManager()->getTrees();
        $data = JsonSegmentHelper::treesResponse($trees);

        return $this->prepareJsonResponse($data);
    }

    /**
     * Remove a tree
     * @param Request $request
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/remove-tree")
     */
    public function removeTreeAction(Request $request)
    {
        $this->getSegmentManager()->removeTreeById($request->get('id'));
        $this->getSegmentManager()->getStorageManager()->flush();

        $data = JsonSegmentHelper::statusOKResponse();

        return $this->prepareJsonResponse($data);
    }

    /**
     * Create a new tree
     * @param Request $request
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/create-tree")
     */
    public function createTreeAction(Request $request)
    {
        $title = $request->get('title');

        $rootSegment = $this->getSegmentManager()->createTree($title);
        $this->getSegmentManager()->getStorageManager()->persist($rootSegment);
        $this->getSegmentManager()->getStorageManager()->flush();

        $data = array('rootId' => $rootSegment->getId());

        return $this->prepareJsonResponse($data);
    }


    /**
     * Return a response in json content type with well formated data
     * @param mixed $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareJsonResponse($data)
    {
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Associate product to the specified segment
     *
     * @param Request $request Request (segment_id, product_id)
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/add-item")
     */
    public function addProductAction(Request $request)
    {
        $segmentId = $request->get('segment_id');
        $productId = $request->get('item_id');

        $repo = $this->getSegmentManager()->getEntityRepository();
        $segment = $repo->find($segmentId);
        $product = $this->getDoctrine()->getManager()
            ->find('AcmeDemoSegmentationTreeBundle:Product', $productId);

        $segment->addProduct($product);
        $this->getSegmentManager()->getStorageManager()->persist($segment);
        $this->getSegmentManager()->getStorageManager()->flush();

        $response = JsonSegmentHelper::statusOKResponse();

        return $this->prepareJsonResponse($response);
    }

    /**
     * Remove association between the specified product and the specified segment
     *
     * @param Request $request Request (segment_id, product_id)
     *
     * @Method("POST")
     * @Route("/remove-item")
     */
    public function removeProductAction(Request $request)
    {
        $segmentId = $request->get('segment_id');
        $productId = $request->get('item_id');

        $repo = $this->getSegmentManager()->getEntityRepository();
        $segment = $repo->find($segmentId);
        $product = $this->getDoctrine()->getManager()
            ->find('AcmeDemoSegmentationTreeBundle:Product', $productId);

        $segment->removeProduct($product);
        $this->getSegmentManager()->getStorageManager()->persist($segment);
        $this->getSegmentManager()->getStorageManager()->flush();

        $response = JsonSegmentHelper::statusOKResponse();

        return $this->prepareJsonResponse($response);
    }

    /**
     * List products associated with the provided segment
     *
     * @param Request $request Request (segment_id)
     *
     * @return Response
     *
     * @Method("GET")
     * @Route("/list-items")
     *
     */
    public function listProductsAction(Request $request)
    {
        $segmentId = $request->get('segment_id');

        $repo = $this->getSegmentManager()->getEntityRepository();
        $segment = $repo->find($segmentId);

        $products = new ArrayCollection();

        if (is_object($segment)) {
            $products = $segment->getProducts();
        }

        $response = JsonSegmentHelper::productsResponse($products);

        return $this->prepareJsonResponse($response);
    }
}
