<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Datetime;
use JMS\Serializer\Annotation as JMS;
use Serializable;

/**
 * Pomodoro
 *
 * @ORM\Table(name="pomodoro")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PomodoroRepository")
 */
class Pomodoro
{
    /**
     * @var int
     * @JMS\Groups({"Pomodoros_getPomodoro", "Pomodoros_getReceived"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     * @JMS\Groups({"Pomodoros_getPomodoro", "Pomodoros_getReceived"})
     * @ORM\Column(name="finished", type="boolean")
     */
    private $finished;

    /**
     * @var int
     * @JMS\Groups({"Pomodoros_getPomodoro", "Pomodoros_getReceived"})
     * @ORM\Column(name="time", type="integer")
     */
    private $time;

    /**
     * @var bool
     * @JMS\Groups({"Pomodoros_getPomodoro", "Pomodoros_getReceived"})
     * @ORM\Column(name="in_progress", type="boolean")
     */
    private $inProgress;

    /**
     * @JMS\Groups({"Pomodoros_getPomodoro", "Pomodoros_getReceived"})
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="pomodoros")
     */
    private $users;

    /**
     * Many Pomodoro have One task.
     * @JMS\Groups({"Pomodoros_getPomodoro", "Pomodoros_getReceived"})
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="pomodoros")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    private $task;

    /**
     * @JMS\Groups({"Pomodoros_getPomodoro", "Pomodoros_getReceived"})
     * One pomodoro has One Sender.
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="sernder_id", referencedColumnName="id")
     */
    private $sender;

    /**
     * @JMS\Groups({"Pomodoros_getPomodoro", "Pomodoros_getReceived"})
     * One pomodoro has One Reciever.
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    private $receiver;

    /**
     * Many Pomodoro have One issue.
     * @JMS\Groups({"Pomodoros_getPomodoro"})
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="pomodoros")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id")
     */
    private $issue;

    /**
     * Many Pomodoro have One task.
     * @JMS\Groups({"Pomodoros_getPomodoro"})
     * @ORM\ManyToOne(targetEntity="ChecklistItem", inversedBy="pomodoros")
     * @ORM\JoinColumn(name="checklist_item_id", referencedColumnName="id")
     */
    private $item;

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
     * Set finished
     *
     * @param boolean $finished
     *
     * @return Pomodoro
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
     * @return Pomodoro
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
     * Set $time
     *
     * @param int $time
     *
     * @return Pomodoro
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get $time
     *
     * @return time
     */
    public function getTime()
    {
        return $this->time;
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
     * @return Pomodoro
     */
    public function setUsers(ArrayCollection $users): self
    {
        $this->users = $users;
        return $this;
    }

    /**
     * Get task.
     *
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * Set task.
     *
     * @param Task $task
     * @return Pomodoro
     */
    public function setTask(Task $task): self
    {
        $this->task = $task;
        return $this;
    }

    /**
     * Get issue.
     *
     * @return Issue
     */
    public function getIssue(): Issue
    {
        return $this->issue;
    }

    /**
     * Set issue.
     *
     * @param Issue $issue
     * @return Pomodoro
     */
    public function setIssue(Issue $issue): self
    {
        $this->issue = $issue;
        return $this;
    }

    /**
     * Get item.
     *
     * @return ChecklistItem
     */
    public function getItem(): ChecklistItem
    {
        return $this->item;
    }

    /**
     * Set item.
     *
     * @param ChecklistItem $item
     * @return Pomodoro
     */
    public function setItem(ChecklistItem $item): self
    {
        $this->item = $item;
        return $this;
    }

    /**
     * Get sender.
     *
     * @return User
     */
    public function getSender(): User
    {
        return $this->sender;
    }

    /**
     * Set sender.
     *
     * @param User $sender
     * @return Pomodoro
     */
    public function setSender(User $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Get receiver.
     *
     * @return User
     */
    public function getReceiver(): User
    {
        return $this->receiver;
    }

    /**
     * Set receiver.
     *
     * @param User $receiver
     * @return Pomodoro
     */
    public function setReceiver(User $receiver): self
    {
        $this->receiver = $receiver;
        return $this;
    }
}

