<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonalCategoriesRepository")
 * @ORM\Table(name="Personal_Categories")
 */
class PersonalCategories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="PersonalCategoriesID", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="categories")
     * @ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID", nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="personalCategories")
     * @ORM\JoinColumn(name="CategoryID", referencedColumnName="CategoryID", nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="boolean", options={"default":1})
     */
    private $visibility;

    /**
     * @ORM\OneToMany(targetEntity="ReceivedEmails", mappedBy="category")
     */
    private $receivedEmails;

    /**
     * @ORM\OneToMany(targetEntity="SentEmails", mappedBy="category")
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
            $receivedEmail->setCategory($this);
        }

        return $this;
    }

    public function removeReceivedEmail(ReceivedEmails $receivedEmail): self
    {
        if ($this->receivedEmails->contains($receivedEmail)) {
            $this->receivedEmails->removeElement($receivedEmail);
            // set the owning side to null (unless already changed)
            if ($receivedEmail->getCategory() === $this) {
                $receivedEmail->setCategory(null);
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
            $sentEmail->setCategory($this);
        }

        return $this;
    }

    public function removeSentEmail(SentEmails $sentEmail): self
    {
        if ($this->sentEmails->contains($sentEmail)) {
            $this->sentEmails->removeElement($sentEmail);
            // set the owning side to null (unless already changed)
            if ($sentEmail->getCategory() === $this) {
                $sentEmail->setCategory(null);
            }
        }

        return $this;
    }
}
