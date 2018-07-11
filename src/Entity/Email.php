<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailRepository")
 * @ORM\Table(name="Email")
 */
class Email
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="EmailID", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID", nullable=false)
     */
    private $member;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $reply_to_email;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\OneToMany(targetEntity="ReceivedEmails", mappedBy="content")
     */
    private $receivedEmails;

    /**
     * @ORM\OneToMany(targetEntity="SentEmails", mappedBy="content")
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

    public function getReplyToEmail(): ?string
    {
        return $this->reply_to_email;
    }

    public function setReplyToEmail(string $reply_to_email): self
    {
        $this->reply_to_email = $reply_to_email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimeStamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

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
            $receivedEmail->setEmail($this);
        }

        return $this;
    }

    public function removeReceivedEmail(ReceivedEmails $receivedEmail): self
    {
        if ($this->receivedEmails->contains($receivedEmail)) {
            $this->receivedEmails->removeElement($receivedEmail);
            // set the owning side to null (unless already changed)
            if ($receivedEmail->getEmail() === $this) {
                $receivedEmail->setEmail(null);
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
            $sentEmail->setEmail($this);
        }

        return $this;
    }

    public function removeSentEmail(SentEmails $sentEmail): self
    {
        if ($this->sentEmails->contains($sentEmail)) {
            $this->sentEmails->removeElement($sentEmail);
            // set the owning side to null (unless already changed)
            if ($sentEmail->getEmail() === $this) {
                $sentEmail->setEmail(null);
            }
        }

        return $this;
    }
}
