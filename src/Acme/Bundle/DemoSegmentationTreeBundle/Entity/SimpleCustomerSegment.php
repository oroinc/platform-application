<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\Entity;

use Oro\Bundle\SegmentationTreeBundle\Model\AbstractSegment;

use Acme\Bundle\DemoSegmentationTreeBundle\Entity\SimpleCustomer;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Segment node entity class implementing tree associated with SimpleCustomer
 *
 * @author    Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @ORM\Entity(repositoryClass="Oro\Bundle\SegmentationTreeBundle\Entity\Repository\SegmentRepository")
 * @ORM\Table(name="acmedemosegmentationtree_simplecustomersegment")
 * @Gedmo\Tree(type="nested")
 */
class SimpleCustomerSegment extends AbstractSegment
{

    /**
     * @var SimpleCustomerSegment $parent
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="SimpleCustomerSegment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection 
     * 
     * @ORM\OneToMany(targetEntity="SimpleCustomerSegment", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"left" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\ManyToMany(targetEntity="SimpleCustomer")
     * @ORM\JoinTable(name="acmedemosegmentationtree_segments_simplecustomers",
     *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="customer_id", referencedColumnName="id")}
     *      )
     **/
    protected $simpleCustomers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->simpleCustomers = new ArrayCollection();
    }

    /**
     * Add customer to this segment node
     *
     * @param SimpleCustomer $customer
     *
     * @return SimpleCustomerSegment
     */
    public function addCustomer(SimpleCustomer $customer)
    {
        $this->simpleCustomers[] = $customer;

        return $this;
    }

    /**
     * Remove customer from this segment node
     *
     * @param SimpleCustomer $customer
     *
     * @return SimpleCustomerSegment
     */
    public function removeCustomer(SimpleCustomer $customer)
    {
        $this->simpleCustomers->removeElement($customer);

        return $this;
    }

    /**
     * Get simple customers from this segment node
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSimpleCustomers()
    {
        return $this->simpleCustomers;
    }

}
