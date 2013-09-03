<?php

namespace Extend\Cache\OroEmailBundle\Entity;

use Oro\Bundle\EmailBundle\Entity\Util\EmailUtil;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use Oro\Bundle\EmailBundle\Entity\EmailAddress;
use Oro\Bundle\EmailBundle\Entity\EmailOwnerInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="oro_email_address",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="oro_email_address_uq", columns={"email"})},
 *      indexes={@ORM\Index(name="oro_email_address_idx", columns={"email"})})
 * @ORM\HasLifecycleCallbacks
 */
class EmailAddressProxy extends EmailAddress
{
    /**
     * @var EmailOwnerInterface
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_user_id", referencedColumnName="id")
     * @Exclude
     */
    private $owner1;

    /**
     * {@inheritdoc}
     */
    public function getOwner()
    {
        if ($this->owner1 !== null) {
            return $this->owner1;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(EmailOwnerInterface $owner = null)
    {
        if (is_a($owner, 'Oro\Bundle\UserBundle\Entity\User')) {
            $this->owner1 = $owner;
        } else {
            $this->owner1 = null;
        }

        return $this;
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function beforeSave()
    {
        $this->created = EmailUtil::currentUTCDateTime();
        $this->updated = EmailUtil::currentUTCDateTime();
    }

    /**
     * Pre update event listener
     *
     * @ORM\PreUpdate
     */
    public function beforeUpdate()
    {
        $this->updated = EmailUtil::currentUTCDateTime();
    }
}
