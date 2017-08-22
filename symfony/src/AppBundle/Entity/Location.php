<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Serializable;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocationRepository")
 */
class Location
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
     *
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\Column(name="alias", type="string", length=255, unique=true)
     */
    private $alias;

    /**
     * @var boolean
     *
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\Column(name="adress", type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @var string
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     */
    private $company;

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
     * Many Location have Many Profiles.
     * @ORM\ManyToMany(targetEntity="Profile", mappedBy="locations")
     */
    private $profiles;


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
     * Set alias
     *
     * @param string $alias
     *
     * @return Location
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Location
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set adress
     *
     * @param string $adress
     *
     * @return Location
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return Location
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Location
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
     * @return Location
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
     * @return Location
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
     * Get profiles.
     *
     * @return Collection
     */
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }
    /**
     * Set profiles.
     *
     * @param ArrayCollection $profiles
     * @return Location
     */
    public function setProfiles(ArrayCollection $profiles): self
    {
        $this->profiles = $profiles;
        return $this;
    }
}

