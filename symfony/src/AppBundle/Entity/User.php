<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Groups;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Serializable;

use DateTime;
/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     * @JMS\Groups({"Users_getUser", "Profiles_getProfile", "getSettings", "Pomodoros_getReceived"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @JMS\Groups({"Groups_getTeams", "Profiles_getProfile"})
     */
    protected $username;
    /**
     * @JMS\Groups({"Profiles_getProfile"})
     */
    protected $email;
    /**
     * @var string
     * @JMS\Groups({"Users_getUser", "Profiles_getProfile", "Groups_getTeams"})
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     * @JMS\Groups({"Users_getUser", "Profiles_getProfile","Groups_getTeams"})
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;



    /**
     *  constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setProjects(new ArrayCollection());
        $this->setGroups(new ArrayCollection());
    }

    /************
     * MAPPING
     ************/
    /**
     * One User has One Profile.
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\OneToOne(targetEntity="Profile", mappedBy="user")
     */
    private $profile;

    /**
     * One User has One Schedule.
     * @ORM\OneToOne(targetEntity="Schedule", inversedBy="user")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id")
     */
    private $schedule;


    /**
     * Many Users have One Department.
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="users")
     *  @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    private $department;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Groups", inversedBy="users")
     * @ORM\JoinTable(name="users_groups",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * Many Users have Many Logs.
     * @ORM\ManyToMany(targetEntity="Log", inversedBy="users")
     * @ORM\JoinTable(name="users_logs",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="log_id", referencedColumnName="id")}
     * )
     */
    private $logs;

    /**
     * Many Users have Many Projects.
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="users")
     * @ORM\JoinTable(name="users_projects",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")}
     * )
     */
    private $projects;

    /**
     * Many Users have Many Pomodoro.
     * @ORM\ManyToMany(targetEntity="Pomodoro", inversedBy="users")
     * @ORM\JoinTable(name="users_pomodoro",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="pomodoro_id", referencedColumnName="id")}
     * )
     */
    private $pomodoros;

    /**
     * One User has Many Task.
     * @ORM\OneToMany(targetEntity="Task", mappedBy="user")
     */
    private $tasks;

    /**
     * Many User have Many Attachment.
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\ManyToMany(targetEntity="Attachment", inversedBy="feedback")
     * @ORM\JoinTable(name="user_attachments",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id")}
     * )
     */
    private $attachments;

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
     * Set first name
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * get profile
     *
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * get Schedule
     *
     * @return User
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * set Schedule
     *
     * @param mixed $schedule
     *
     * @return User
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
        return $this;
    }
    /**
     * Get department.
     *
     * @return Department
     */
    public function getDepartment(): Department
    {
        return $this->department;
    }

    /**
     * Set Department.
     *
     * @param Department $department
     * @return User
     */
    public function setDepartment(Department $department): self
    {
        $this->department = $department;
        return $this;
    }

    /**
     * Add group.
     *
     * @param Groups $group
     * @return User
     */
    public function addGroup(\FOS\UserBundle\Model\GroupInterface $group)
    {
        $this->groups->add($group);
        return $this;
    }
    /**
     * Get groups.
     *
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }
    /**
     * Set groups.
     *
     * @param ArrayCollection $groups
     * @return User
     */
    public function setGroups(ArrayCollection $groups): self
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * Add project.
     *
     * @param Project $project
     * @return User
     */
    public function addProject(Project $project): self
    {
        $this->projects->add($project);
        return $this;
    }
    /**
     * Get project.
     *
     * @return Collection
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }
    /**
     * Set project.
     *
     * @param ArrayCollection $project
     * @return User
     */
    public function setProjects(ArrayCollection $projects): self
    {
        $this->projects = $projects;
        return $this;
    }

    /**
     * Add log.
     *
     * @param Log $log
     * @return User
     */
    public function addLog(Log $log): self
    {
        $this->logs->add($log);
        return $this;
    }
    /**
     * Get logs.
     *
     * @return Collection
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }
    /**
     * Set logs.
     *
     * @param ArrayCollection $logs
     * @return User
     */
    public function setLogs(ArrayCollection $logs): self
    {
        $this->logs = $logs;
        return $this;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return User
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
     * Add pomodoro.
     *
     * @param Pomodoro $pomodoro
     * @return User
     */
    public function addPomodoro(Pomodoro $pomodoro): self
    {
        $this->pomodoros->add($pomodoro);
        return $this;
    }
    /**
     * Get pomodoros.
     *
     * @return Collection
     */
    public function getPomodoros(): Collection
    {
        return $this->pomodoros;
    }

    /**
     * Set pomodoros.
     *
     * @param ArrayCollection $pomodoros
     * @return User
     */
    public function setPomodoros(ArrayCollection $pomodoros): self
    {
        $this->pomodoros = $pomodoros;
        return $this;
    }

    /**
     * Add attachment.
     *
     * @param Attachment $attachment
     * @return User
     */
    public function addAttachment(Attachment $attachment): self
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
     * @return User
     */
    public function setAttachments(ArrayCollection $attachments): self
    {
        $this->attachments = $attachments;
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


}

