<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
/**

 * Schedule
 *
 * @ORM\Table(name="schedule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ScheduleRepository")
 */
class Schedule
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
     * @ORM\Column(name="start_time", type="time", nullable=true)
     */
    private $startTime;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="end_time", type="time", nullable=true)
     */
    private $endTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="full_day", type="boolean")
     */
    private $fullDay;

    /**
     * @var bool
     *
     * @ORM\Column(name="half_a_day", type="boolean")
     */
    private $halfADay;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="end_date", type="date", unique=true)
     */
    private $endDate;


    /**
     * One User has One Profile.
     * @ORM\OneToOne(targetEntity="User", mappedBy="schedule")
     */
    private $user;

    ///
    /// SCHEMA HEEFT PROJECTEN
    ///

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
     * Set startTime
     *
     * @param DateTime $startTime
     *
     * @return Schedule
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param DateTime $endTime
     *
     * @return Schedule
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set fullDay
     *
     * @param boolean $fullDay
     *
     * @return Schedule
     */
    public function setFullDat($fullDay)
    {
        $this->fullDay = $fullDay;

        return $this;
    }

    /**
     * Get fullDay
     *
     * @return bool
     */
    public function getFullDat()
    {
        return $this->fullDay;
    }

    /**
     * Set halfADay
     *
     * @param boolean $halfADay
     *
     * @return Schedule
     */
    public function setHalfADay($halfADay)
    {
        $this->halfADay = $halfADay;

        return $this;
    }

    /**
     * Get halfADay
     *
     * @return bool
     */
    public function getHalfADay()
    {
        return $this->halfADay;
    }

    /**
     * Set date
     *
     * @param DateTime $date
     *
     * @return Schedule
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set endDate
     *
     * @param DateTime $endDate
     *
     * @return Schedule
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * get user
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}

