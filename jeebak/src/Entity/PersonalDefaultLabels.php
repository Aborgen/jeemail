<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonalDefaultLabelsRepository")
 * @ORM\Table(name="Personal_Default_Labels")
 */
class PersonalDefaultLabels
{
    const JOIN_TABLE = "Default_Label::class";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="PersonalDefaultLabelsID", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="defaultLabels")
     * @ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID", nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="DefaultLabel", inversedBy="personalDefaultLabels")
     * @ORM\JoinColumn(name="DefaultLabelID", referencedColumnName="DefaultLabelID", nullable=false)
     */
    private $label;

    /**
     * @ORM\Column(type="boolean", options={"default":1})
     */
    private $visibility;

    /**
     * @ORM\OneToMany(targetEntity="ReceivedSentEmailsToLabels", mappedBy="defaultLabels")
     */
    private $sentOrReceivedEmails;

    public function __construct()
    {
        $this->sentOrReceivedEmails = new ArrayCollection();
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

    public function getLabel(): ?DefaultLabel
    {
        return $this->label;
    }

    public function setLabel(?DefaultLabel $label): self
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
     * @return Collection|ReceivedSentEmailsToLabels[]
     */
    public function getSentOrReceivedEmails(): Collection
    {
        return $this->sentOrReceivedEmails;
    }

    public function addSentOrReceivedEmail(ReceivedSentEmailsToLabels $sentOrReceivedEmail): self
    {
        if (!$this->sentOrReceivedEmails->contains($sentOrReceivedEmail)) {
            $this->sentOrReceivedEmails[] = $sentOrReceivedEmail;
            $sentOrReceivedEmail->setDefaultLabels($this);
        }

        return $this;
    }

    public function removeReceivedEmail(ReceivedSentEmailsToLabels $sentOrReceivedEmail): self
    {
        if ($this->sentOrReceivedEmails->contains($sentOrReceivedEmail)) {
            $this->sentOrReceivedEmails->removeElement($sentOrReceivedEmail);
            // set the owning side to null (unless already changed)
            if ($sentOrReceivedEmail->getDefaultLabels() === $this) {
                $sentOrReceivedEmail->setDefaultLabels(null);
            }
        }

        return $this;
    }
}
