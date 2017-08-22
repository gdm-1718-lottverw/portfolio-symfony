<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Serializable;

/**
 * Profile
 *
 * @ORM\Table(name="profiles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileRepository")
 */
class Profile
{
    /**
     * @var int
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var \DateTime
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var \DateTime
     * @JMS\Groups({"Profiles_getProfile"})
     * @ORM\Column(name="first_login", type="datetime", nullable=true)
     */
    private $firstLogin;

    public function __construct()
    {
        $this->setLocations(new ArrayCollection());
    }

    /****
     * MAPPING
     ****/

    /**
     * One Profile has One User.
     * @ORM\OneToOne(targetEntity="User", inversedBy="profile")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @JMS\Groups({"Profiles_getProfile"})
     * Many Profiles have Many Locations.
     * @ORM\ManyToMany(targetEntity="Location", inversedBy="profiles")
     * @ORM\JoinTable(name="profiles_locations",
     *     joinColumns={@ORM\JoinColumn(name="profile_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="location_id", referencedColumnName="id")}
     * )
     */
    private $locations;


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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Profile
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set lastLogin
     *
     * @param DateTime $lastLogin
     *
     * @return Profile
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set firstLogin
     *
     * @param DateTime $firstLogin
     *
     * @return Profile
     */
    public function setFirstLogin($firstLogin)
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    /**
     * Get firstLogin
     *
     * @return DateTime
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    /**
     * get User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * set User
     *
     * @param mixed $user
     *
     * @return Profile
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Add location.
     *
     * @param Location $location
     * @return Profile
     */
    public function addLocation(Location $location): self
    {
        $this->locations->add($location);
        return $this;
    }
    /**
     * Get locations.
     *
     * @return Collection
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }
    /**
     * Set locations.
     *
     * @param ArrayCollection $locations
     * @return Profile
     */
    public function setLocations(ArrayCollection $locations): self
    {
        $this->locations = $locations;
        return $this;
    }
}

