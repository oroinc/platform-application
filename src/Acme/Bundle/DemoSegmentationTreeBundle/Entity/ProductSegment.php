<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\Entity;

use Oro\Bundle\SegmentationTreeBundle\Entity\AbstractSegment;

use Acme\Bundle\DemoSegmentationTreeBundle\Entity\Product;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Segment node entity class implementing tree associated with Product
 *
 * @author    Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @ORM\Entity(repositoryClass="Oro\Bundle\SegmentationTreeBundle\Entity\Repository\SegmentRepository")
 * @ORM\Table(name="acme_demosegmentationtree_productsegment")
 * @Gedmo\Tree(type="nested")
 */
class ProductSegment extends AbstractSegment
{

    /**
     * @var ProductSegmento $parent
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="ProductSegment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProductSegment", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"left" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\ManyToMany(targetEntity="Product")
     * @ORM\JoinTable(name="acme_demosegmentationtree_segments_products",
     *      joinColumns={@ORM\JoinColumn(name="segment_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     **/
    protected $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Products = new ArrayCollection();
    }

    /**
     * Add product to this segment node
     *
     * @param Product $product
     *
     * @return ProductSegment
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product from this segment node
     *
     * @param Product $product
     *
     * @return ProductSegment
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * Get  products from this segment node
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
