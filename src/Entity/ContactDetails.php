<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactDetailsRepository")
 * @ORM\Table(name="Contact_Details", options={"comment":"Aggregate details from a particular entry in Member_Contacts. Can be entirely null (apart from Conact_DetailsID, of course)."})
 */
class ContactDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="ContactDetailsID", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=64, nullable=true, options={"check": "CHECK (type IN ('business', 'personal'))"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $job_title;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $relationship;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="PersonalContacts", mappedBy="contactDetails")
     */
    private $personalContacts;

    public function __construct()
    {
        $this->personalContacts = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->job_title;
    }

    public function setJobTitle(?string $job_title): self
    {
        $this->job_title = $job_title;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getRelationship(): ?string
    {
        return $this->relationship;
    }

    public function setRelationship(?string $relationship): self
    {
        $this->relationship = $relationship;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collection|PersonalContacts[]
     */
    public function getPersonalContacts(): Collection
    {
        return $this->personalContacts;
    }

    public function addPersonalContact(PersonalContacts $personalContact): self
    {
        if (!$this->personalContacts->contains($personalContact)) {
            $this->personalContacts[] = $personalContact;
            $personalContact->setContactDetails($this);
        }

        return $this;
    }

    public function removePersonalContact(PersonalContacts $personalContact): self
    {
        if ($this->personalContacts->contains($personalContact)) {
            $this->personalContacts->removeElement($personalContact);
            // set the owning side to null (unless already changed)
            if ($personalContact->getContactDetails() === $this) {
                $personalContact->setContactDetails(null);
            }
        }

        return $this;
    }
}
