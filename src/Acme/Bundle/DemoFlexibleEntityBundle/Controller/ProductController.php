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
        return $this->container->get('product_manager');
    }

    /**
     * Get attribute codes
     * @return array
     */
    protected function getAttributeCodesToDisplay()
    {
        return array('name', 'description', 'size', 'color');
    }

    /**
     * @Route("/index")
     * @Template()
     *
     * @return multitype
     */
    public function indexAction()
    {
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes();

        return array('products' => $products, 'attributes' => $this->getAttributeCodesToDisplay());
    }

    /**
     * @Route("/querylazyload")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querylazyloadAction()
    {
        // get only entities, values and attributes are lazy loaded
        // you can use any criteria, order you want it's a classic doctrine query
        $products = $this->getProductManager()->getEntityRepository()->findBy(array());

        return array('products' => $products, 'attributes' => $this->getAttributeCodesToDisplay());
    }

    /**
     * @Route("/queryonlyname")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function queryonlynameAction()
    {
        // get all entity fields and directly get attributes values
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('name'));

        return array('products' => $products, 'attributes' => array('name'));
    }

    /**
     * @Route("/querynameanddesc")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynameanddescAction()
    {
        // get all entity fields and directly get attributes values
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('name', 'description'));

        return array('products' => $products, 'attributes' => array('name', 'description'));
    }

    /**
     * @Route("/querynameanddescforcelocale")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynameanddescforcelocaleAction()
    {
        // get all entity fields and directly get attributes values
        $pm = $this->getProductManager();
        // force, always in french
        $pm->setLocaleCode('fr');
        $products = $pm->getEntityRepository()->findByWithAttributes(array('name', 'description'));

        return array('products' => $products, 'attributes' => array('name', 'description'));
    }

    /**
     * @Route("/queryfilterskufield")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function queryfilterskufieldAction()
    {
        // get all entity fields, directly get attributes values, filter on entity field value
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array(), array('sku' => 'sku-2'));

        return array('products' => $products, 'attributes' => array());
    }

    /**
     * @Route("/querynamefilterskufield")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynamefilterskufieldAction()
    {
        // get all entity fields, directly get attributes values, filter on entity field value
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('name'), array('sku' => 'sku-2'));

        return array('products' => $products, 'attributes' => array('name'));
    }

    /**
     * @Route("/queryfiltersizeattribute")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function queryfiltersizeattributeAction()
    {
        // get all entity fields, directly get attributes values, filter on attribute value
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('description', 'size'), array('size' => 175));

        return array('products' => $products, 'attributes' => array('description', 'size'));
    }

    /**
     * @Route("/queryfiltersizeanddescattributes")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function queryfiltersizeanddescattributesAction()
    {
        // get all entity fields, directly get attributes values, filter on attribute value
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(
            array('size', 'description'),
            array('size' => 175, 'description' => 'my other description')
        );

        return array('products' => $products, 'attributes' => array('description', 'size'));
    }

    /**
     * @Route("/querynameanddesclimit")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynameanddesclimitAction()
    {
        // get all entity fields and directly get attributes values
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(array('name', 'description'), null, null, 10, 0);

        return array('products' => $products, 'attributes' => array('name', 'description'));
    }

    /**
     * @Route("/querynameanddescorderby")
     * @Template("AcmeDemoFlexibleEntityBundle:Product:index.html.twig")
     *
     * @return multitype
     */
    public function querynameanddescorderbyAction()
    {
        // get all entity fields and directly get attributes values
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes(
            array('name', 'description'), null, array('description' => 'desc', 'id' => 'asc')
        );

        return array('products' => $products, 'attributes' => array('name', 'description'));
    }

    /**
     * @param integer $id
     *
     * @Route("/view/{id}")
     * @Template()
     *
     * @return multitype
     */
    public function viewAction($id)
    {
        // with lazy loading
        //$product = $this->getProductManager()->getEntityRepository()->find($id);
        // with any values
        $product = $this->getProductManager()->getEntityRepository()->findWithAttributes($id);

        return array('product' => $product);
    }

    /**
     * List product attributes
     * @Route("/atribute")
     * @Template()
     *
     * @return multitype
     */
    public function attributeAction()
    {
        $attributes = $this->getProductManager()->getAttributeRepository()
            ->findBy(array('entityType' => $this->getProductManager()->getEntityName()));

        return array('attributes' => $attributes);
    }

}
