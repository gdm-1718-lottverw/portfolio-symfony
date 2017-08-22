<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Serializable;

/**
 * CustomerFeedback
 *
 * @ORM\Table(name="customer_feedback")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerFeedbackRepository")
 */
class CustomerFeedback
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
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="feedback", type="text", nullable=true)
     */
    private $feedback;

    /**
     * @var string
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="request", type="text", nullable=true)
     */
    private $request;

    /**
     * @var \DateTime
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="submitted_at", type="datetime")
     */
    private $submittedAt;

    /**
     * @var bool
     * @JMS\Groups({"Admin_getProjects"})
     * @ORM\Column(name="answered", type="boolean")
     */
    private $answered;

    /**
     * Many Issues have One Project.
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="feedback")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * Many Projects have Many Attachment.
     * @ORM\ManyToMany(targetEntity="Attachment", inversedBy="feedback")
     * @ORM\JoinTable(name="feedback_attachments",
     *     joinColumns={@ORM\JoinColumn(name="feedback_id", referencedColumnName="id")},
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
     * Set feedback
     *
     * @param string $feedback
     *
     * @return CustomerFeedback
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * Get feedback
     *
     * @return string
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * Set request
     *
     * @param string $request
     *
     * @return CustomerFeedback
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set submittedAt
     *
     * @param \DateTime $submittedAt
     *
     * @return CustomerFeedback
     */
    public function setSubmittedAt($submittedAt)
    {
        $this->submittedAt = $submittedAt;

        return $this;
    }

    /**
     * Get submittedAt
     *
     * @return \DateTime
     */
    public function getSubmittedAt()
    {
        return $this->submittedAt;
    }

    /**
     * Set answered
     *
     * @param boolean $answered
     *
     * @return CustomerFeedback
     */
    public function setAnswered($answered)
    {
        $this->answered = $answered;

        return $this;
    }

    /**
     * Get answered
     *
     * @return bool
     */
    public function getAnswered()
    {
        return $this->answered;
    }

    /**
     * Get project.
     *
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * Set project.
     *
     * @param Project $project
     * @return CustomerFeedback
     */
    public function setProject(Project $project): self
    {
        $this->project = $project;
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
     * @return CustomerFeedback
     */
    public function setAttachments(ArrayCollection $attachments): self
    {
        $this->attachments = $attachments;
        return $this;
    }
}

