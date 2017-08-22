<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use JMS\Serializer\Annotation as JMS;
use Serializable;
/**
 * Work
 *
 * @ORM\Table(name="work")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkRepository")
 */
class Work
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
     * @var int
     * @JMS\Groups({"getSettings"})
     * @ORM\Column(name="hours", type="integer", length=255)
     */
    private $hours;

    /**
     * @var int
     * @JMS\Groups({"getSettings"})
     * @ORM\Column(name="minutes", type="integer", length=255)
     */
    private $minutes;

    /**
     * @var int
     * @JMS\Groups({"getSettings"})
     * @ORM\Column(name="seconds", type="integer", length=255)
     */
    private $seconds;

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
     * Set hours
     *
     * @param integer $hours
     *
     * @return Work
     */
    public function setHours($hours)
    {
        $this->hours = $hours;

        return $this;
    }

    /**
     * Get hours
     *
     * @return int
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Set minutes
     *
     * @param integer $minutes
     *
     * @return Work
     */
    public function setMinutes($minutes)
    {
        $this->minutes = $minutes;

        return $this;
    }

    /**
     * Get minutes
     *
     * @return int
     */
    public function getMinutes()
    {
        return $this->minutes;
    }


    /**
     * Set seconds
     *
     * @param integer $seconds
     *
     * @return Work
     */
    public function setSeconds($seconds)
    {
        $this->seconds = $seconds;

        return $this;
    }

    /**
     * Get seconds
     *
     * @return int
     */
    public function getSeconds()
    {
        return $this->seconds;
    }

}

