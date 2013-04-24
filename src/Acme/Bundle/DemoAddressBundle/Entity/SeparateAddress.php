<?php

namespace Acme\Bundle\DemoAddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\AddressBundle\Entity\AddressBase;

/**
 * SeparateAddress
 *
 * @ORM\Table("oro_service_address")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Oro\Bundle\AddressBundle\Entity\Repository\AddressRepository")
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="postalCode",
 *          column=@ORM\Column(
 *              name     = "postal_code",
 *              type     = "string",
 *              length   = 20,
 *              nullable = true
 *          )
 *      )
 * })
 */
class SeparateAddress extends AddressBase
{
    /**
     * @var \Oro\Bundle\FlexibleEntityBundle\Model\AbstractFlexibleValue[]
     *
     * @ORM\OneToMany(targetEntity="Acme\Bundle\DemoAddressBundle\Entity\Value\AddressValue", mappedBy="entity", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $values;

    /******************************************************* ADDING ADDITIONAL FIELDS *************************************************************/
    /**
     * @var string
     * @ORM\Column(name="working_hours", type="string", length=255, nullable=true)
     */
    private $workingHours;

    /**
     * Get working hours
     *
     * @return string
     */
    public function getWorkingHours()
    {
        return $this->workingHours;
    }

    /**
     * Set working hours
     *
     * @param string $workingHours
     * @return $this
     */
    public function setWorkingHours($workingHours)
    {
        $this->workingHours = $workingHours;

        return $this;
    }
    /************************************************* END OF ADDING ADDITIONAL FIELDS *************************************************************/
}
