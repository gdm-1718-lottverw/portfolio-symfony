<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Serializable;

/**
 * ChecklistItem
 *
 * @ORM\Table(name="checklist_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChecklistItemRepository")
 */
class ChecklistItem
{
    /**
     * @var int
     * @JMS\Groups({"Items_getItem"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @JMS\Groups({"Items_getItem", "Admin_getProjects"})
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @JMS\Groups({"Items_getItem"})
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;

    /**
     * @var string
     * @JMS\Groups({"Items_getItem"})
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @var string
     * @JMS\Groups({"Items_getItem"})
     * @ORM\Column(name="completed_by", type="string", length=255, nullable=true)
     */
    private $completedBy;

    /**
     * @var string
     * @JMS\Groups({"Items_getItem"})
     * @ORM\Column(name="is_working", type="string", length=255, nullable=true)
     */
    private $isWorking;

    /**
     * @var bool
     * @JMS\Groups({"Items_getItem", "Admin_getProjects"})
     * @ORM\Column(name="finished", type="boolean")
     */
    private $finished;

    /**
     * @var bool
    * @JMS\Groups({"Items_getItem"})
     * @ORM\Column(name="in_progress", type="boolean")
     */
    private $inProgress;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;
   
    /**
     * @var int
     * @JMS\Groups({"Items_getItem"})
     * @ORM\Column(name="estimate_pomodoros", type="integer")
     */
    private $estimatePomodoros;

    /**
     * Many Items have One Checklist.
     * @ORM\ManyToOne(targetEntity="Checklist", inversedBy="item")
     * @ORM\JoinColumn(name="checklist_id", referencedColumnName="id")
     */
    private $checklist;

     /**
     * One Item has Many Pomodoros.
     * @ORM\OneToMany(targetEntity="Pomodoro", mappedBy="item")
     */
    private $pomodoros;

    /**
     * constructor.
     */
    public function __construct()
    {
        $datetime = new DateTime();
        $this->setCreatedAt($datetime);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return ChecklistItem
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
    /**
     * Set name
     *
     * @param string $name
     *
     * @return ChecklistItem
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
     * @param string $description
     *
     * @return ChecklistItem
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
     * Set completedBy
     *
     * @param string $completedBy
     *
     * @return ChecklistItem
     */
    public function setCompletedBy($completedBy)
    {
        $this->completedBy = $completedBy;

        return $this;
    }

    /**
     * Get completedBy
     *
     * @return string
     */
    public function getCompletedBy()
    {
        return $this->completedBy;
    }


    /**
     * Set isWorking
     *
     * @param string $isWorking
     *
     * @return ChecklistItem
     */
    public function setIsWorking($isWorking)
    {
        $this->isWorking= $isWorking;

        return $this;
    }

    /**
     * Get isWorking
     *
     * @return string
     */
    public function getIsWorking()
    {
        return $this->isWorking;
    }


    /**
     * Set finished
     *
     * @param boolean $finished
     *
     * @return ChecklistItem
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return bool
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set inProgress
     *
     * @param boolean $inProgress
     *
     * @return ChecklistItem
     */
    public function setInProgress($inProgress)
    {
        $this->inProgress = $inProgress;

        return $this;
    }

    /**
     * Get inProgress
     *
     * @return bool
     */
    public function getInProgress()
    {
        return $this->inProgress;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return ChecklistItem
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return ChecklistItem
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

   /**
     * Set estimatePomodoros
     *
     * @param int $estimatePomodoros
     *
     * @return ChecklistItem
     */
    public function setEstimatePomodoros($estimatePomodoros)
    {
        $this->estimatePomodoros = $estimatePomodoros;

        return $this;
    }

    /**
     * Get estimatePomodoros
     *
     * @return int
     */
    public function getEstimatePomodoros()
    {
        return $this->estimatePomodoros;
    }
    /**
     * Set deletedAt
     *
     * @param DateTime $deletedAt
     *
     * @return ChecklistItem
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }


    /**
     * Get checklist.
     *
     * @return Checklist
     */
    public function getChecklist(): Checklist
    {
        return $this->checklist;
    }

    /**
     * Set checklist.
     *
     * @param Checklist $checklist
     * @return ChecklistItem
     */
    public function setChecklist(Checklist $checklist): self
    {
        $this->checklist = $checklist;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPomodoro(): Collection
    {
        return $this->pomodoros;
    }

    /**
     * @param ArrayCollection $pomodoros
     *
     * @return ChecklistItem
     */
    public function setPomodoro(ArrayCollection $pomodoros): self
    {
        $this->pomodoros = $pomodoros;
        return $this;
    }

}

