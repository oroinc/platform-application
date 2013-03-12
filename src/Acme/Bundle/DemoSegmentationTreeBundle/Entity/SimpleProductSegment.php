<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\Entity;

use Oro\Bundle\SegmentationTreeBundle\Model\AbstractSegment;

use Acme\Bundle\DemoSegmentationTreeBundle\Entity\SimpleProduct;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Segment node entity class implementing tree associated with SimpleProduct
 *
 * @author    Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @ORM\Entity(repositoryClass="Oro\Bundle\SegmentationTreeBundle\Entity\Repository\SegmentRepository")
 * @ORM\Table(name="acmedemosegmentationtree_simpleproductsegment")
 * @Gedmo\Tree(type="nested")
 */
class SimpleProductSegment extends AbstractSegment
{

    /**
     * @var SimpleProductSegmento $parent
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="SimpleProductSegment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection 
     * 
     * @ORM\OneToMany(targetEntity="SimpleProductSegment", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"left" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\ManyToMany(targetEntity="SimpleProduct")
     * @ORM\JoinTable(name="acmesegmentationtree_segments_simpleproducts",
     *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     **/
    protected $simpleProducts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->simpleProducts = new ArrayCollection();
    }

    /**
     * Add product to this segment node
     *
     * @param SimpleProduct $product
     *
     * @return SimpleProductSegment
     */
    public function addProduct(SimpleProduct $product)
    {
        $this->simpleProducts[] = $product;

        return $this;
    }

    /**
     * Remove product from this segment node
     *
     * @param SimpleProduct $product
     *
     * @return SimpleProductSegment
     */
    public function removeProduct(SimpleProduct $product)
    {
        $this->simpleProducts->removeElement($product);

        return $this;
    }

    /**
     * Get simple products from this segment node
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSimpleProducts()
    {
        return $this->simpleProducts;
    }

}
