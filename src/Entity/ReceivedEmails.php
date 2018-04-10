<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReceivedEmailsRepository")
 * @ORM\Table(name="Received_Emails")
 */
class ReceivedEmails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="ReceivedEmailsID", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="receivedEmails")
     * @ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID", nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Email", inversedBy="receivedEmails")
     * @ORM\JoinColumn(name="EmailID", referencedColumnName="EmailID", nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $important;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $starred;

    /**
     * @ORM\ManyToOne(targetEntity="PersonalCategories", inversedBy="receivedEmails")
     * @ORM\JoinColumn(name="PersonalCategoriesID", referencedColumnName="PersonalCategoriesID", nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="PersonalLabels", inversedBy="receivedEmails")
     * @ORM\JoinColumn(name="PersonalLabelsID", referencedColumnName="PersonalLabelsID", nullable=true)
     */
    private $labels;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function setEmail(?Email $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getImportant(): ?bool
    {
        return $this->important;
    }

    public function setImportant(bool $important): self
    {
        $this->important = $important;

        return $this;
    }

    public function getStarred(): ?bool
    {
        return $this->starred;
    }

    public function setStarred(bool $starred): self
    {
        $this->starred = $starred;

        return $this;
    }

    public function getCategory(): ?PersonalCategories
    {
        return $this->category;
    }

    public function setCategory(?PersonalCategories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLabels(): ?PersonalLabels
    {
        return $this->labels;
    }

    public function setLabels(?PersonalLabels $labels): self
    {
        $this->labels = $labels;

        return $this;
    }
}
