<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReceivedSentEmailsToLabelsRepository")
 * @ORM\Table(name="Received_Sent_Emails_To_Labels",
 *            options={
 *                "comment":"This table is responsible for collecting the various labels that could be applied to each Sent_Email and Received_Email.",
 *                "check": "CHECK ( ((ReceivedEmailsID IS NOT NULL OR SentEmailsID IS NOT NULL) AND (ReceivedEmailsID IS NULL OR SentEmailsID IS NULL)) AND ((PersonalDefaultLabelsID IS NOT NULL OR PersonalLabelsID IS NOT NULL) AND (PersonalDefaultLabelsID IS NULL OR PersonalLabelsID IS NULL)) )"
 *            })
 */
class ReceivedSentEmailsToLabels
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="ReceivedSentEmailsToLabelsID", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ReceivedEmails", inversedBy="labels")
     * @ORM\JoinColumn(name="ReceivedEmailsID", referencedColumnName="ReceivedEmailsID", nullable=true)
     */
    private $receivedEmail;

    /**
     * @ORM\ManyToOne(targetEntity="SentEmails", inversedBy="labels")
     * @ORM\JoinColumn(name="SentEmailsID", referencedColumnName="SentEmailsID", nullable=true)
     */
    private $sentEmail;

    /**
     * @ORM\ManyToOne(targetEntity="PersonalDefaultLabels", inversedBy="sentOrReceivedEmails")
     * @ORM\JoinColumn(name="PersonalDefaultLabelsID", referencedColumnName="PersonalDefaultLabelsID", nullable=true)
     */
    private $defaultLabels;

    /**
     * @ORM\ManyToOne(targetEntity="PersonalLabels", inversedBy="sentOrReceivedEmails")
     * @ORM\JoinColumn(name="PersonalLabelsID", referencedColumnName="PersonalLabelsID", nullable=true)
     */
    private $labels;

    public function getId()
    {
        return $this->id;
    }

    public function getReceivedEmail(): ?ReceivedEmails
    {
        return $this->receivedEmail;
    }

    public function setReceivedEmail(?ReceivedEmails $receivedEmail): self
    {
        $this->receivedEmail = $receivedEmail;

        return $this;
    }

    public function getSentEmail(): ?SentEmails
    {
        return $this->sentEmail;
    }

    public function setSentEmail(?SentEmails $sentEmail): self
    {
        $this->sentEmail = $sentEmail;

        return $this;
    }

    public function getDefaultLabels(): ?PersonalDefaultLabels
    {
        return $this->defaultLabels;
    }

    public function setDefaultLabels(?PersonalDefaultLabels $defaultLabels): self
    {
        $this->defaultLabels = $defaultLabels;

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
