<?php

namespace Acme\Bundle\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\Bundle\DemoBundle\Entity\Product
 *
 * @ORM\Table(name="demo_product")
 */
class Product
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var decimal $price
     *
     * @ORM\Column(name="price", type="decimal")
     */
    private $price;

    /**
     * @var integer $count
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var /DateTime $createDate
     *
     * @ORM\Column(name="create_date", type="datetime", nullable=true)
     */
    private $createDate;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Manufacturer", inversedBy="products")
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id")
     */
    private $manufacturer;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
     * @ORM\JoinTable(name="demo_category_product",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $categories;

    /**
     * @var Value
     *
     * @ORM\OneToMany(targetEntity="ProductValue", mappedBy="entity", cascade={"persist", "remove"})
     */
    protected $values;

    /**
     * Set name
     *
     * @param  string  $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set description
     *
     * @param  string  $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set manufacturer
     *
     * @param  \Acme\Bundle\DemoBundle\Entity\Manufacturer $manufacturer
     * @return Product
     */
    public function setManufacturer(\Acme\Bundle\DemoBundle\Entity\Manufacturer $manufacturer = null)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer
     *
     * @return \Acme\Bundle\DemoBundle\Entity\Manufacturer
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Add categories
     *
     * @param  \Acme\Bundle\DemoBundle\Entity\Category $category
     * @return Product
     */
    public function addCategorie(\Acme\Bundle\DemoBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Acme\Bundle\DemoBundle\Entity\Category $category
     */
    public function removeCategorie(\Acme\Bundle\DemoBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set price
     *
     * @param  float   $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set count
     *
     * @param  integer $count
     * @return Product
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set createDate
     *
     * @param  \DateTime $createDate
     * @return Product
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function __toString()
    {
        return $this->name;
    }
}
