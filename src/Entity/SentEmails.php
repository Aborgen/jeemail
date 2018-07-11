<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SentEmailsRepository")
 * @ORM\Table(name="Sent_Emails")
 */
class SentEmails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="SentEmailsID", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="sentEmails")
     * @ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID", nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Email", inversedBy="sentEmails")
     * @ORM\JoinColumn(name="EmailID", referencedColumnName="EmailID", nullable=false)
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $important;

    /**
     * @ORM\Column(type="boolean")
     */
    private $starred;

    /**
     * @ORM\ManyToOne(targetEntity="PersonalCategories", inversedBy="sentEmails")
     * @ORM\JoinColumn(name="PersonalCategoriesID", referencedColumnName="PersonalCategoriesID", nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReceivedSentEmailsToLabels", mappedBy="sentEmail")
     */
    private $labels;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
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

    public function getContent(): ?Email
    {
        return $this->email;
    }

    public function setContent(?Email $content): self
    {
        $this->email = $content;

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
            $label->setSentEmail($this);
        }

        return $this;
    }

    public function removeLabel(ReceivedSentEmailsToLabels $label): self
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
            // set the owning side to null (unless already changed)
            if ($label->getSentEmail() === $this) {
                $label->setSentEmail(null);
            }
        }

        return $this;
    }
}
