<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Serializable;
/**
 * TimerSettings
 *
 * @ORM\Table(name="timer_settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimerSettingsRepository")
 */
class TimerSettings
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
     * One Setting has One Pause.
     * @JMS\Groups({"getSettings"})
     * @ORM\OneToOne(targetEntity="Pause", cascade={"persist"})
     * @ORM\JoinColumn(name="pause_time_id", referencedColumnName="id")
     */
    private $pause_time;

    /**
     * One Setting has One Work.
     * @JMS\Groups({"getSettings"})
     * @ORM\OneToOne(targetEntity="Work", cascade={"persist"})
     * @ORM\JoinColumn(name="work_time_id", referencedColumnName="id")
     */
    private $work_time;

    /**
     * One Setting has One User.
     * @JMS\Groups({"getSettings"})
     * @ORM\OneToOne(targetEntity="User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * get pause_time
     *
     * @return Pause
     */
    public function getPauseTime()
    {
        return $this->pause_time;
    }

    /**
     * set pause_time
     *
     * @param mixed $pause_time
     *
     * @return TimerSettings
     */
    public function setPauseTime($pause_time)
    {
        $this->pause_time = $pause_time;
        return $this;
    }

    /**
     * get work_time
     *
     * @return Work
     */
    public function getWorkTime()
    {
        return $this->work_time;
    }

    /**
     * set work_time
     *
     * @param mixed $work_time
     *
     * @return TimerSettings
     */
    public function setWorkTime($work_time)
    {
        $this->work_time = $work_time;
        return $this;
    }

    /**
     * get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * set user
     *
     * @param mixed $user
     *
     * @return TimerSettings
     */
    public function setUser($user)
    {
        $this->user= $user;
        return $this;
    }

}

