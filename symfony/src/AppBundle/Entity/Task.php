<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use JMS\Serializer\Annotation as JMS;
use Serializable;
/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 */
class Task
{
    /**
     * @var int
     * @JMS\Groups({"Tasks_getTask"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     * @JMS\Groups({"Tasks_getTask"})
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;

    /**
     * @var string
     * @JMS\Groups({"Tasks_getTask", "Admin_getProjects"})
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @JMS\Groups({"Tasks_getTask", "Admin_getProjects"})
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool
     * @JMS\Groups({"Tasks_getTask", "Admin_getProjects"})
     * @ORM\Column(name="finished", type="boolean")
     */
    private $finished;

    /**
     * @var int
     * @JMS\Groups({"Tasks_getTask"})
     * @ORM\Column(name="estimate_pomodoros", type="integer")
     */
    private $estimatePomodoros;

    /**
     * @var string
     * @JMS\Groups({"Tasks_getTask"})
     * @ORM\Column(name="completed_by", type="string", length=255, nullable=true)
     */
    private $completedBy;

    /**
     * @var bool
     * @JMS\Groups({"Tasks_getTask"})
     * @ORM\Column(name="in_progress", type="boolean")
     */
    private $inProgress;

    /**
     * @var string
     * @JMS\Groups({"Tasks_getTask"})
     * @ORM\Column(name="is_working", type="string", length=255, nullable=true)
     */
    private $isWorking;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     */
    private $finishedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Many Tasks have One Project.
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="tasks")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * Many Tasks have One User.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * One Task has Many Pomodoros.
     * @ORM\OneToMany(targetEntity="Pomodoro", mappedBy="task")
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
     * @return Task
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
     * @return Task
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
     * Set estimatePomodoros
     *
     * @param int $estimatePomodoros
     *
     * @return Task
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
     * Set description
     *
     * @param string $description
     *
     * @return Task
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
     * Set finished
     *
     * @param boolean $finished
     *
     * @return Task
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
     * Set completedBy
     *
     * @param string $completedBy
     *
     * @return Task
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
     * Set inProgress
     *
     * @param boolean $inProgress
     *
     * @return Task
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
     * Set isWorking
     *
     * @param string $isWorking
     *
     * @return Task
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
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return Task
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
     * Set finishedAt
     *
     * @param DateTime $finishedAt
     *
     * @return Task
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return Task
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
     * @return Task
     */
    public function setProject(Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Set project.
     *
     * @param User $user
     * @return Task
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
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
     * @return Task
     */
    public function setPomodoro(ArrayCollection $pomodoros): self
    {
        $this->pomodoros = $pomodoros;
        return $this;
    }

}

