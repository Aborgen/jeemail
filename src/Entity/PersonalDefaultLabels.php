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
    private $defaultLabel;

    /**
     * @ORM\Column(type="boolean", options={"default":1})
     */
    private $visibility;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReceivedSentEmailsToLabels", mappedBy="defaultLabels")
     */
    private $receivedEmail;

    public function __construct()
    {
        $this->receivedEmail = new ArrayCollection();
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

    public function getDefaultLabel(): ?DefaultLabel
    {
        return $this->defaultLabel;
    }

    public function setDefaultLabel(?DefaultLabel $defaultLabel): self
    {
        $this->defaultLabel = $defaultLabel;

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
    public function getReceivedEmail(): Collection
    {
        return $this->receivedEmail;
    }

    public function addReceivedEmail(ReceivedSentEmailsToLabels $receivedEmail): self
    {
        if (!$this->receivedEmail->contains($receivedEmail)) {
            $this->receivedEmail[] = $receivedEmail;
            $receivedEmail->setDefaultLabels($this);
        }

        return $this;
    }

    public function removeReceivedEmail(ReceivedSentEmailsToLabels $receivedEmail): self
    {
        if ($this->receivedEmail->contains($receivedEmail)) {
            $this->receivedEmail->removeElement($receivedEmail);
            // set the owning side to null (unless already changed)
            if ($receivedEmail->getDefaultLabels() === $this) {
                $receivedEmail->setDefaultLabels(null);
            }
        }

        return $this;
    }
}
