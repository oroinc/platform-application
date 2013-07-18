<?php

namespace Acme\Bundle\DemoWorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone conversation entity
 *
 * @ORM\Table(name="acme_demo_workflow_phone_conversation")
 * @ORM\Entity
 */
class PhoneConversation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var PhoneCall
     *
     * @ORM\OneToOne(targetEntity="PhoneCall")
     * @ORM\JoinColumn(name="call_id", referencedColumnName="id")
     */
    private $call;

    /**
     * @var string
     *
     * @ORM\Column(name="result", type="string", length=255)
     */
    private $result;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="successful", type="boolean")
     */
    private $successful;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set result
     *
     * @param string $result
     * @return PhoneConversation
     */
    public function setResult($result)
    {
        $this->result = $result;
    
        return $this;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return PhoneConversation
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set successful
     *
     * @param boolean $successful
     * @return PhoneConversation
     */
    public function setSuccessful($successful)
    {
        $this->successful = $successful;
    
        return $this;
    }

    /**
     * Is successful
     *
     * @return boolean 
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * @param PhoneCall $call
     * @return PhoneConversation
     */
    public function setCall($call)
    {
        $this->call = $call;

        return $this;
    }

    /**
     * @return PhoneCall
     */
    public function getCall()
    {
        return $this->call;
    }
}
