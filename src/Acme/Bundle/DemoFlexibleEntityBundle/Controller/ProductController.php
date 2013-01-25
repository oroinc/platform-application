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
     * @Route("/index/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template()
     *
     * @return multitype
     */
    public function indexAction($dataLocale, $dataScope)
    {
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes();

        return array('products' => $products, 'attributes' => $this->getAttributeCodesToDisplay());
    }

    /**
     * @Route("/querylazyload/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querylazyloadAction($dataLocale, $dataScope)
    {
        // get only entities, values and attributes are lazy loaded
        // you can use any criteria, order you want it's a classic doctrine query
        $products = $this->getProductManager()->getEntityRepository()->findBy(array());

        return array('products' => $products, 'attributes' => $this->getAttributeCodesToDisplay());
    }

    /**
     * @Route("/queryonlyname/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function queryonlynameAction($dataLocale, $dataScope)
    {
        // get all entity fields and directly get attributes values
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('name'));

        return array('products' => $products, 'attributes' => array('name'));
    }

    /**
     * @Route("/querynameanddesc/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynameanddescAction($dataLocale, $dataScope)
    {
        // get all entity fields and directly get attributes values
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('name', 'description'));

        return array('products' => $products, 'attributes' => array('name', 'description'));
    }

    /**
     * @Route("/querynameanddescforcelocale/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynameanddescforcelocaleAction($dataLocale, $dataScope)
    {
        // get all entity fields and directly get attributes values
        $pm = $this->getProductManager();
        // force, always in french
        $pm->setLocale('fr_FR');
        $products = $pm->getEntityRepository()->findByWithAttributes(array('name', 'description'));

        return array('products' => $products, 'attributes' => array('name', 'description'));
    }

    /**
     * @Route("/queryfilterskufield/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function queryfilterskufieldAction($dataLocale, $dataScope)
    {
        // get all entity fields, directly get attributes values, filter on entity field value
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array(), array('sku' => 'sku-2'));

        return array('products' => $products, 'attributes' => array());
    }

    /**
     * @Route("/querynamefilterskufield/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynamefilterskufieldAction($dataLocale, $dataScope)
    {
        // get all entity fields, directly get attributes values, filter on entity field value
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('name'), array('sku' => 'sku-2'));

        return array('products' => $products, 'attributes' => array('name'));
    }

    /**
     * @Route("/queryfiltersizeattribute/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function queryfiltersizeattributeAction($dataLocale, $dataScope)
    {
        // get all entity fields, directly get attributes values, filter on attribute value
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('description', 'size'), array('size' => 175));

        return array('products' => $products, 'attributes' => array('description', 'size'));
    }

    /**
     * @Route("/queryfiltersizeanddescattributes/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function queryfiltersizeanddescattributesAction($dataLocale, $dataScope)
    {
        // get all entity fields, directly get attributes values, filter on attribute value
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(
            array('size', 'description'),
            array('size' => 175, 'description' => 'my other description(ecommerce)')
        );

        return array('products' => $products, 'attributes' => array('description', 'size'));
    }

    /**
     * @Route("/querynameanddesclimit/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynameanddesclimitAction($dataLocale, $dataScope)
    {
        // get all entity fields and directly get attributes values
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('name', 'description'), null, null, 10, 0);

        return array('products' => $products, 'attributes' => array('name', 'description'));
    }

    /**
     * @Route("/querynameanddescorderby/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynameanddescorderbyAction($dataLocale, $dataScope)
    {
        // get all entity fields and directly get attributes values
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(
            array('name', 'description'), null, array('description' => 'desc', 'id' => 'asc')
        );

        return array('products' => $products, 'attributes' => array('name', 'description'));
    }

    /**
     * Show details
     *
     * @param integer $id         id
     * @param string  $dataLocale data locale
     * @param string  $dataScope  data scope
     *
     * @Route("/view/{id}/{dataLocale}/{dataScope}", defaults={"dataLocale" = null, "dataScope" = null})
     * @Template()
     *
     * @return multitype
     */
    public function viewAction($id, $dataLocale, $dataScope)
    {
        // load with any values
        $product = $this->getProductManager()->getEntityRepository()->findWithAttributes($id);

        return array('product' => $product);
    }

}
