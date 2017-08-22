<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Serializable;
/**
 * Issue
 *
 * @ORM\Table(name="issue")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IssueRepository")
 */
class Issue
{
    /**
     * @var int
     * @JMS\Groups({"Issues_getIssue"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
   
    /**
     * @var string
     * @JMS\Groups({"Issues_getIssue"})
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;
  
    /**
     * @var string
     * @JMS\Groups({"Issues_getIssue", "Admin_getProjects"})
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @JMS\Groups({"Issues_getIssue"})
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="solved", type="boolean")
     */
    private $solved;

    /**
     * @var string
     *
     * @ORM\Column(name="solved_by", type="string", length=255, nullable=true)
     */
    private $solvedBy;

    /**
     * @var bool
     *
     * @ORM\Column(name="in_progress", type="boolean")
     */
    private $inProgress;

    /**
     * @var string
     *
     * @ORM\Column(name="is_working", type="string", length=255, nullable=true)
     */
    private $isWorking;

    /**
     * @var bool
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="urgent", type="boolean")
     */
    private $urgent;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="estimate_pomodoros", type="integer")
     */
    private $estimatePomodoros;

    /**
     * Many Issues have One Project.
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="issues")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

     /**
     * One Issue has Many Pomodoros.
     * @ORM\OneToMany(targetEntity="Pomodoro", mappedBy="issue")
     */
    private $pomodoros;

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
     * @return Issue
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
     * Set title
     *
     * @param string $title
     *
     * @return Issue
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Issue
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
     * Set solved
     *
     * @param boolean $solved
     *
     * @return Issue
     */
    public function setSolved($solved)
    {
        $this->solved = $solved;

        return $this;
    }

    /**
     * Get solved
     *
     * @return bool
     */
    public function getSolved()
    {
        return $this->solved;
    }

    /**
     * Set solvedBy
     *
     * @param string $solvedBy
     *
     * @return Issue
     */
    public function setSolvedBy($solvedBy)
    {
        $this->solvedBy= $solvedBy;

        return $this;
    }

    /**
     * Get solvedBy
     *
     * @return string
     */
    public function getSolvedBy()
    {
        return $this->solvedBy;
    }

    /**
     * Set isWorking
     *
     * @param string $isWorking
     *
     * @return Issue
     */
    public function setIsWorking($isWorking)
    {
        $this->isWorking= $isWorking;

        return $this;
    }

    /**
     * Set inProgress
     *
     * @param boolean $inProgress
     *
     * @return Issue
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
     * Get isWorking
     *
     * @return string
     */
    public function getIsWorking()
    {
        return $this->isWorking;
    }

    /**
     * Set urgent
     *
     * @param boolean $urgent
     *
     * @return Issue
     */
    public function setUrgent($urgent)
    {
        $this->urgent = $urgent;

        return $this;
    }

    /**
     * Get urgent
     *
     * @return bool
     */
    public function getUrgent()
    {
        return $this->urgent;
    }  
    
    /**
     * Set estimatePomodoros
     *
     * @param int $estimatePomodoros
     *
     * @return Issue
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Issue
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Issue
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Issue
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Get project.
     *
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * Set project.
     *
     * @param Project $project
     * @return Issue
     */
    public function setProject(Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPomodoro()
    {
        return $this->pomodoros;
    }

    /**
     * @param ArrayCollection $pomodoros
     *
     * @return Issue
     */
    public function setPomodoro(ArrayCollection $pomodoros)
    {
        $this->pomodoros = $pomodoros;
        return $this;
    }
}

