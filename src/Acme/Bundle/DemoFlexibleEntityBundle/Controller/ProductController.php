<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Product Controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/product")
 */
class ProductController extends Controller
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
     * Get attribute codes
     * @return array
     */
    protected function getAttributeCodesToDisplay()
    {
        return array('name', 'description', 'size', 'color', 'price');
    }

    /**
     * Index action
     *
     * @param string $dataLocale locale
     * @param string $dataScope  scope
     *
     * @Route("/index/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template()
     *
     * @return array
     */
    public function indexAction($dataLocale, $dataScope)
    {
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes();

        return array('products' => $products, 'attributes' => $this->getAttributeCodesToDisplay());
    }

    /**
     * Lazy load action
     * @param string $dataLocale locale
     * @param string $dataScope  scope
     *
     * @Route("/querylazyload/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return array
     */
    public function querylazyloadAction($dataLocale, $dataScope)
    {
        // get only entities, values and attributes are lazy loaded
        // you can use any criteria, order you want it's a classic doctrine query
        $products = $this->getProductManager()->getEntityRepository()->findBy(array());

        return array('products' => $products, 'attributes' => $this->getAttributeCodesToDisplay());
    }

    /**
     * Customer query action
     *
     * @param string $dataLocale locale
     * @param string $dataScope  scope
     * @param string $attributes attribute codes
     * @param string $criteria   criterias
     * @param string $orderBy    order by
     * @param int    $limit      limit
     * @param int    $offset     offset
     *
     * @Route("/query/{dataLocale}/{dataScope}/{attributes}/{criteria}/{orderBy}/{limit}/{offset}",
     *         defaults={"dataLocale" = null, "dataScope" = null, "attributes" = null, "criteria" = null, "orderBy" = null, "limit" = null, "offset" = null})
     *
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return array
     */
    public function queryAction($dataLocale, $dataScope, $attributes, $criteria, $orderBy, $limit, $offset)
    {
        // prepare params
        if (!is_null($attributes) and $attributes !== 'null') {
            $attributes = explode('&', $attributes);
        } else {
            $attributes = array();
        }
        if (!is_null($criteria) and $criteria !== 'null') {
            parse_str($criteria, $criteria);
        } else {
            $criteria = array();
        }
        if (!is_null($orderBy) and $orderBy !== 'null') {
            parse_str($orderBy, $orderBy);
        } else {
            $orderBy = array();
        }

        // get entities
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(
            $attributes, $criteria, $orderBy, $limit, $offset
        );

        return array('products' => $products, 'attributes' => $this->getAttributeCodesToDisplay());
    }

    /**
     * Show details
     *
     * @param integer $id         id
     * @param string  $dataLocale data locale
     * @param string  $dataScope  data scope
     *
     * @Route("/show/{id}/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template()
     *
     * @return array
     */
    public function showAction($id, $dataLocale, $dataScope)
    {
        // load with any values
        $product = $this->getProductManager()->getEntityRepository()->findWithAttributes($id);

        return array('product' => $product);
    }

}
