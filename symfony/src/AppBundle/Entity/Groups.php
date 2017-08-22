<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Serializable;
/**
 * Groups
 * @ORM\Table(name="groups")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupsRepository")
 */
class Groups extends BaseGroup
{
    /**
     * @var int
     * @JMS\Groups({"Groups_getGroup", "Admin_getProjects", "Groups_getTeams"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @JMS\Groups({"Admin_getProjects", "Groups_getTeams"})
     */
    protected $name;

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
     * @JMS\Groups({"Groups_getGroup", "Groups_getTeams"})
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    private $users;

    /**
     * One Group has Many Projects.
     * @ORM\OneToMany(targetEntity="Project", mappedBy="group")
     */
    private $projects;

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
     * Set name
     *
     * @param string $name
     *
     * @return Groups
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Groups
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
     * @return Groups
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
     * @return Groups
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
     * @return Groups
     */
    public function setUsers(ArrayCollection $users): self
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }
    /**
     * @param ArrayCollection $projects
     *
     * @return Groups
     */
    public function setProjects(ArrayCollection $projects): self
    {
        $this->projects = $projects;
        return $this;
    }


}

