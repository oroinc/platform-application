<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\Controller;

use Acme\Bundle\DemoSegmentationTreeBundle\Helper\JsonTreeHelper;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Segment controller
 *
 * @author Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/simple-product-segment")
 *
 */
class SimpleProductSegmentController extends Controller
{
    /**
     * Redirect to index action
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToIndex()
    {
        return $this->redirect($this->generateUrl('demo_segmentationtree_simpleproductsegment_index'));
    }

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
        return $this->render('AcmeDemoSegmentationTreeBundle:SimpleProductSegment:index.html.twig');
    }

    /**
     * Send children segments linked to the one which id is provided
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Method("GET")
     * @Route("/children")
     * @Template()
     *
     */
    public function childrenAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $parentId = $request->get('id');

            $segments = $this->getManager()->getChildren($parentId);

            $data = JsonTreeHelper::childrenResponse($segments);

            return $this->prepareJsonResponse($data);
        } else {
            return $this->redirectToIndex();
        }
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
        if ($request->isXmlHttpRequest()) {
            $search = $request->get('search_str');

            $segments = $this->getManager()->search(array('title' => $search));

            $data = JsonTreeHelper::searchResponse($segments);

            return $this->prepareJsonResponse($data);
        } else {
            return $this->redirectToIndex();
        }
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
        if ($request->isXmlHttpRequest()) {
            $parentId = $request->get('id');
            $title = $request->get('title');
            
            $segment = $this->getManager()->createSegment();

            $segment->setTitle($title);

            $repo = $this->getManager()->getEntityRepository();
            $parent = $repo->find($parentId);

            $segment->setParent($parent);

            $this->getManager()->getStorageManager()->persist($segment);
            $this->getManager()->getStorageManager()->flush();

            $data = JsonTreeHelper::createNodeResponse(1, $segment->getId());

            return $this->prepareJsonResponse($data);
        } else {
            return $this->redirectToIndex();
        }
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
        if ($request->isXmlHttpRequest()) {
            $this->getManager()->rename($request->get('id'), $request->get('title'));

            $data = JsonTreeHelper::statusOKResponse();

            return $this->prepareJsonResponse($data);
        } else {
            return $this->redirectToIndex();
        }
    }

    /**
     * Remove a node
     * @param Request $request
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/remove-node")
     * @Template()
     */
    public function removeAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $this->getManager()->removeFromId($request->get('id'));

            $data = JsonTreeHelper::statusOKResponse();

            return $this->prepareJsonResponse($data);
        } else {
            return $this->redirectToIndex();
        }
    }

    /**
     * Move a node
     * @param Request $request
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/move-node")
     * @Template()
     */
    public function moveNodeAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $segmentId = $request->get('id');
            $referenceId = $request->get('ref');

            if ($request->get('copy') == 1) {
                $this->getManager()->copy($segmentId, $referenceId);
            } else {
                $this->getManager()->move($segmentId, $referenceId);
            }

            // format response to json content
            $data = JsonTreeHelper::statusOKResponse();

            return $this->prepareJsonResponse($data);
        } else {
            return $this->redirectToIndex();
        }
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
     * @return SegmentManager
     */
    protected function getManager()
    {
        return $this->get('acme_demo_segmentation_tree.simpleproduct_segment_manager');
    }

    /**
     * List simple products associated with the provided segment
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Method("GET")
     * @Route("/list-products")
     * @Template()
     *
     */
    public function listProductsAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $segmentId = $request->get('segment_id');
            $simpleProducts = new ArrayCollection();

            $repo = $this->getManager()->getEntityRepository();
            $segment = $repo->find($segmentId);

            if (is_object($segment)) {
                $simpleProducts = $segment->getSimpleProducts();
            }

            $response = JsonTreeHelper::simpleProductsResponse($simpleProducts);
            return $this->prepareJsonResponse($response);
        } else {
            return $this->redirectToIndex();
        }
    }

    /**
     * Associate product to the specified segment
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/associate-product")
     * @Template()
     *
     * TODO: Manage multiple product addition for future grid use
     */
    public function associateProductAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $segmentId = $request->get('segment_id');
            $productId = $request->get('product_id');
            
            $repo = $this->getManager()->getEntityRepository();
            $segment = $repo->find($segmentId);
            $product = $this->getDoctrine()->getManager()
                ->find('AcmeDemoSegmentationTreeBundle:SimpleProduct',$productId);

            $segment->addProduct($product);
            $this->getManager()->getStorageManager()->persist($segment);
            $this->getManager()->getStorageManager()->flush();

            $response = JsonTreeHelper::statusOKResponse();

            return $this->prepareJsonResponse($response);
        } else {
            return $this->redirectToIndex();
        }
    }

    /**
     * Remove association with product to the specified segment
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Method("POST")
     * @Route("/detach-product")
     * @Template()
     *
     * TODO: Manage multiple product addition for future grid use
     */
    public function detachProductAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $segmentId = $request->get('segment_id');
            $productId = $request->get('product_id');
            
            $repo = $this->getManager()->getEntityRepository();
            $segment = $repo->find($segmentId);
            $product = $this->getDoctrine()->getManager()
                ->find('AcmeDemoSegmentationTreeBundle:SimpleProduct',$productId);

            $segment->removeProduct($product);
            $this->getManager()->getStorageManager()->persist($segment);
            $this->getManager()->getStorageManager()->flush();

            $response = JsonTreeHelper::statusOKResponse();

            return $this->prepareJsonResponse($response);
        } else {
            return $this->redirectToIndex();
        }
    }

    
}
