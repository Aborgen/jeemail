<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonalLabelsRepository")
 * @ORM\Table(name="Personal_Labels")
 */
class PersonalLabels
{
    const JOIN_TABLE = "Label";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="PersonalLabelsID", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="labels")
     * @ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID", nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Label", inversedBy="personalLabels")
     * @ORM\JoinColumn(name="LabelID", referencedColumnName="LabelID", nullable=false)
     */
    private $label;

    /**
     * @ORM\Column(type="boolean", options={"default":1})
     */
    private $visibility;

    /**
     * @ORM\OneToMany(targetEntity="ReceivedEmails", mappedBy="labels")
     */
    private $receivedEmails;

    /**
     * @ORM\OneToMany(targetEntity="SentEmails", mappedBy="labels")
     */
    private $sentEmails;

    public function __construct()
    {
        $this->receivedEmails = new ArrayCollection();
        $this->sentEmails     = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getLabel(): ?Label
    {
        return $this->label;
    }

    public function setLabel(?Label $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @return Collection|ReceivedEmails[]
     */
    public function getReceivedEmails(): Collection
    {
        return $this->receivedEmails;
    }

    public function addReceivedEmail(ReceivedEmails $receivedEmail): self
    {
        if (!$this->receivedEmails->contains($receivedEmail)) {
            $this->receivedEmails[] = $receivedEmail;
            $receivedEmail->setLabels($this);
        }

        return $this;
    }

    public function removeReceivedEmail(ReceivedEmails $receivedEmail): self
    {
        if ($this->receivedEmails->contains($receivedEmail)) {
            $this->receivedEmails->removeElement($receivedEmail);
            // set the owning side to null (unless already changed)
            if ($receivedEmail->getLabels() === $this) {
                $receivedEmail->setLabels(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SentEmails[]
     */
    public function getSentEmails(): Collection
    {
        return $this->sentEmails;
    }

    public function addSentEmail(SentEmails $sentEmail): self
    {
        if (!$this->sentEmails->contains($sentEmail)) {
            $this->sentEmails[] = $sentEmail;
            $sentEmail->setLabels($this);
        }

        return $this;
    }

    public function removeSentEmail(SentEmails $sentEmail): self
    {
        if ($this->sentEmails->contains($sentEmail)) {
            $this->sentEmails->removeElement($sentEmail);
            // set the owning side to null (unless already changed)
            if ($sentEmail->getLabels() === $this) {
                $sentEmail->setLabels(null);
            }
        }

        return $this;
    }
}
