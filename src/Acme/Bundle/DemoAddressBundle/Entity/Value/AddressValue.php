<?php

namespace Acme\Bundle\DemoAddressBundle\Entity\Value;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Oro\Bundle\FlexibleEntityBundle\Entity\Attribute;
use Oro\Bundle\FlexibleEntityBundle\Entity\Mapping\AbstractEntityFlexibleValue;

/**
 * @ORM\Table(name="oro_service_address_attribute_value")
 * @ORM\Entity
 */
class AddressValue extends AbstractEntityFlexibleValue
{
    /**
     * @var Attribute $attribute
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\FlexibleEntityBundle\Entity\Attribute")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $attribute;

    /**
     * @var \Acme\Bundle\DemoAddressBundle\Entity\SeparateAddress $entity
     *
     * @ORM\ManyToOne(targetEntity="Acme\Bundle\DemoAddressBundle\Entity\SeparateAddress", inversedBy="values")
     */
    protected $entity;

    /**
     * Store options and theirs values
     *
     * @var ArrayCollection $options
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\FlexibleEntityBundle\Entity\AttributeOption")
     * @ORM\JoinTable(
     *     name="oro_service_address_value_option",
     *     joinColumns={@ORM\JoinColumn(name="value_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="option_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $options;
}
