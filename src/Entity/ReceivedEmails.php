<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private $content;

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
     * @ORM\JoinColumn(name="PersonalCategoriesID", referencedColumnName="PersonalCategoriesID", nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReceivedSentEmailsToLabels", mappedBy="receivedEmail")
     */
    private $labels;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $email_read;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContent(): ?Email
    {
        return $this->content;
    }

    public function setContent(?Email $content): self
    {
        $this->content = $content;

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

    /**
     * @return Collection|ReceivedSentEmailsToLabels[]
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(ReceivedSentEmailsToLabels $label): self
    {
        if (!$this->labels->contains($label)) {
            $this->labels[] = $label;
            $label->setReceivedEmail($this);
        }

        return $this;
    }

    public function removeLabel(ReceivedSentEmailsToLabels $label): self
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
            // set the owning side to null (unless already changed)
            if ($label->getReceivedEmail() === $this) {
                $label->setReceivedEmail(null);
            }
        }

        return $this;
    }

    public function getEmailRead(): ?bool
    {
        return $this->email_read;
    }

    public function setEmailRead(bool $email_read): self
    {
        $this->email_read = $email_read;

        return $this;
    }
}
