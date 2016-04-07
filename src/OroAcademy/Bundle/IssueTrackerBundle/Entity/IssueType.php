<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="oroacademy_issue_types")
 * @ORM\HasLifecycleCallbacks()
 */
class IssueType
{
    const TYPE_BUG = 'bug';
    const TYPE_STORY = 'story';
    const TYPE_TASK = 'task';
    const TYPE_SUB_TASK = 'sub_task';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="name")
     */
    protected $name;

    /**
     * @var ArrayCollection|Issue[]
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="type")
     */
    protected $issues;

    /**
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_BUG,
            self::TYPE_STORY,
            self::TYPE_TASK,
            self::TYPE_SUB_TASK
        ];
    }

    /**
     * IssueType constructor.
     *
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
        $this->issues = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection|Issue[]
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @param ArrayCollection|Issue[] $issues
     */
    public function setIssues($issues)
    {
        $this->issues = $issues;
    }

    /**
     * @param Issue $issue
     * @return $this
     */
    public function addIssue(Issue $issue)
    {
        $this->issues->add($issue);

        return $this;
    }

    /**
     * @param Issue $issue
     * @return $this
     */
    public function removeIssue(Issue $issue)
    {
        $this->issues->removeElement($issue);

        return $this;
    }
}
