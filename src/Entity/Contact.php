<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 * @ORM\Table(name="Contact", options={"comment":"If a name and email combo already exists when a member tries to create a contact, assign that particular ContactID."},
 *            uniqueConstraints={@ORM\UniqueConstraint(name="name__email",columns={"name", "email"})})
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="ContactID", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64, options={"comment":"Should be able to query for Member using email."})
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="PersonalContacts", mappedBy="contact")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
            $personalContact->setContact($this);
        }

        return $this;
    }

    public function removePersonalContact(PersonalContacts $personalContact): self
    {
        if ($this->personalContacts->contains($personalContact)) {
            $this->personalContacts->removeElement($personalContact);
            // set the owning side to null (unless already changed)
            if ($personalContact->getContact() === $this) {
                $personalContact->setContact(null);
            }
        }

        return $this;
    }
}
