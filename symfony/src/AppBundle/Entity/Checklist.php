<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Serializable;

/**
 * Checklist
 *
 * @ORM\Table(name="checklist")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChecklistRepository")
 */
class Checklist
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
     * One Checklist has Many Items.
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\OneToMany(targetEntity="ChecklistItem", mappedBy="checklist")
     */
    private $items;
    
    /**
     * One Checklist has One Project.
     * @ORM\OneToOne(targetEntity="Project", mappedBy="checklist", cascade={"persist"})
     */
    private $project;
    
    /**
     * constructor.
     */
    public function __construct()
    {
        $datetime = new DateTime();
        $this->setCreatedAt($datetime);
        $this->setItems(new ArrayCollection());
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
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return Checklist
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
     * @return Checklist
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
     * Set deletedAt
     *
     * @param DateTime $deletedAt
     *
     * @return Checklist
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
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     *
     * @return ChecklistItem
     */
    public function setItems(ArrayCollection $items): self
    {
        $this->items = $items;
        return $this;
    }

    /**
     * get project
     *
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }
}

