<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonalContactsRepository")
 * @ORM\Table(name="Personal_Contacts")
 */
class PersonalContacts
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="PersonalContactsID", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="contacts")
     * @ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID", nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="personalContacts")
     * @ORM\JoinColumn(name="ContactID", referencedColumnName="ContactID", nullable=false)
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="ContactDetails", inversedBy="personalContacts")
     * @ORM\JoinColumn(name="ContactDetailsID", referencedColumnName="ContactDetailsID", nullable=false)
     */
    private $contactDetails;

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

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getContactDetails(): ?ContactDetails
    {
        return $this->contactDetails;
    }

    public function setContactDetails(?ContactDetails $contactDetails): self
    {
        $this->contactDetails = $contactDetails;

        return $this;
    }
}
