<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
/**
 * Attachment
 *
 * @ORM\Table(name="attachment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttachmentRepository")
 */
class Attachment
{
    /**
     * @var int
     * @JMS\Groups({"Attachments_getAttachment"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @JMS\Groups({"Attachments_getAttachment"})
     * @ORM\Column(name="src", type="string", length=255)
     */
    private $src;

    /**
     * @var string
     * @JMS\Groups({"Attachments_getAttachment"})
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Groups({"Attachments_getAttachment"})
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload a picture.")
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png" })
     */
    private $file;

    /**
     * @var string
     * @JMS\Groups({"Attachments_getAttachment"})
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var \DateTime
     * @JMS\Groups({"Attachments_getAttachment"})
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @JMS\Groups({"Attachments_getAttachment"})
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     * @JMS\Groups({"Attachments_getAttachment"})
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Many Attachment have Many Projects.
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="attachments")
     */
    private $projects;

    /**
     * Many Attachment have Many user.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="attachments")
     */
    private $users;

    /**
     * Many Attachment have Many feedback.
     * @ORM\ManyToMany(targetEntity="CustomerFeedback", mappedBy="attachments")
     */
    private $feedback;
    
    /**
     *  constructor.
     */
    public function __construct()
    {
        $this->setUsers(new ArrayCollection());
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
     * Set src
     *
     * @param string $src
     *
     * @return Attachment
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Attachment
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
     * Set file
     *
     * @param string $file
     *
     * @return Attachment
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }



    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Attachment
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Attachment
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
     * @return Attachment
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
     * @return Attachment
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
     * @return Collection
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * Set project.
     *
     * @param ArrayCollection $projects
     * @return Attachment
     */
    public function setProjects(ArrayCollection $projects): self
    {
        $this->projects = $projects;
        return $this;
    }

    /**
     * Get feedback.
     *
     * @return Collection
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    /**
     * Set feedback.
     *
     * @param ArrayCollection $feedback
     * @return Attachment
     */
    public function setFeedback(ArrayCollection $feedback): self
    {
        $this->feedback = $feedback;
        return $this;
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
     * Set users
     *
     * @param ArrayCollection $users
     * @return Attachment
     */
    public function setUsers(ArrayCollection $users): self
    {
        $this->users = $users;
        return $this;
    }

}

