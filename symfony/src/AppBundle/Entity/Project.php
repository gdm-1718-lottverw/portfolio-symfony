<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Serializable;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var string
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="customer", type="string", length=255)
     */
    private $customer;

    /**
     * @var string
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="hash", type="string", length=255, unique=true)
     */
    private $hash;

    /**
     * @var \DateTime
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="deadline", type="datetime")
     */
    private $deadline;

    /**
     * @var string
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="finished", type="boolean")
     */
    private $finished;

    /**
     * @var bool
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="in_progress", type="boolean")
     */
    private $inProgress;
    /**
     * @var \DateTime
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     */
    private $finishedAt;
    /**
     * @var \DateTime
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
     * Many Projects have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="projects")
     */
    private $users;
    
    /**
     *  constructor.
     */
    public function __construct()
    {
        $this->setUsers(new ArrayCollection());
    }

    /**
     * Many Projects have Many Attachment.
     * @ORM\ManyToMany(targetEntity="Attachment", inversedBy="projects")
     * @ORM\JoinTable(name="projects_attachments",
     *     joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id")}
     * )
     */
    private $attachments;

    /**
     * Many Projects have One Group.
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="projects")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * One Project has Many Issue.
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="project")
     */
    private $issues;

    /**
     * One Project has Many Feedback.
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\OneToMany(targetEntity="CustomerFeedback", mappedBy="project")
     */
    private $feedback;

    /**
     * One Project has Many Task.
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\OneToMany(targetEntity="Task", mappedBy="project")
     */
    private $tasks;

    /**
     * One Profile has One Checklist.
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\OneToOne(targetEntity="Checklist", inversedBy="project")
     * @ORM\JoinColumn(name="checklist_id", referencedColumnName="id")
     */
    private $checklist;

  

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
     * Set title
     *
     * @param string $title
     *
     * @return Project
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
     * Set customer
     *
     * @param string $customer
     *
     * @return Project
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return string
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Project
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
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return Project
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
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
     * @return Project
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
     * @return Project
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
     * @param \DateTime $createdAt
     *
     * @return Project
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
     * Set finishedAt
     *
     * @param \DateTime $finishedAt
     *
     * @return Project
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Project
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
     * @return Project
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
     * Get users.
     *
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * Set users.
     *
     * @param ArrayCollection $users
     * @return Project
     */
    public function setUsers(ArrayCollection $users): self
    {
        $this->users = $users;
        return $this;
    }

    /**
     * Get group.
     *
     * @return Groups
     */
    public function getGroup(): Groups
    {
        return $this->group;
    }

    /**
     * Set group.
     *
     * @param Groups $group
     * @return Project
     */
    public function setGroup(Groups $group): self
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getIssues(): Collection
    {
        return $this->issues;
    }

    /**
     * @param ArrayCollection $issues
     *
     * @return Issue
     */
    public function setIssues(ArrayCollection $issues): self
    {
        $this->issues = $issues;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    /**
     * @param ArrayCollection $feedback
     *
     * @return CustomerFeedback
     */
    public function setFeedBack(ArrayCollection $feedback): self
    {
        $this->feedback = $feedback;
        return $this;
    }


    /**
     * @return Collection
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param ArrayCollection $tasks
     *
     * @return Task
     */
    public function setTasks(ArrayCollection $tasks): self
    {
        $this->tasks = $tasks;
        return $this;
    }

    /**
     * Add attachment.
     *
     * @param Groups $attachment
     * @return Project
     */
    public function addAttachment(Groups $attachment): self
    {
        $this->attachments->add($attachment);
        return $this;
    }

    /**
     * Get attachments.
     *
     * @return Collection
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    /**
     * Set attachments.
     *
     * @param ArrayCollection $attachments
     * @return Project
     */
    public function setAttachments(ArrayCollection $attachments): self
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * get Checklist
     *
     * @return Checklist
     */
    public function getChecklist()
    {
        return $this->checklist;
    }

    /**
     * set Checklist
     *
     * @param mixed $checklist
     *
     * @return Project
     */
    public function setChecklist($checklist)
    {
        $this->checklist = $checklist;
        return $this;
    }
}

