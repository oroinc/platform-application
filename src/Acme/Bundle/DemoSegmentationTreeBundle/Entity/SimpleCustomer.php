<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Simple customer entity class
 *
 * @author    Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @ORM\Entity
 * @ORM\Table(name="acmedemosegmentationtree_simplecustomer")
 */
class SimpleCustomer
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    protected $name;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    protected $phone;


    /**
     * Get id of the current customer
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SimpleCustomer
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
     * Set phone
     *
     * @param string $phone
     *
     * @return SimpleCustomer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
