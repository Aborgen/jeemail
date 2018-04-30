<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlockedRepository")
 * @ORM\Table(name="Blocked")
 */
class Blocked
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="BlockedID", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="PersonalBlockeds", mappedBy="blocked")
     */
    private $personalBlockeds;

    public function __construct()
    {
        $this->personalBlockeds = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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
     * @return Collection|PersonalBlockeds[]
     */
    public function getPersonalBlockeds(): Collection
    {
        return $this->personalBlockeds;
    }

    public function addPersonalBlocked(PersonalBlockeds $personalBlocked): self
    {
        if (!$this->personalBlockeds->contains($personalBlocked)) {
            $this->personalBlockeds[] = $personalBlocked;
            $personalBlocked->setBlocked($this);
        }

        return $this;
    }

    public function removePersonalBlocked(PersonalBlockeds $personalBlocked): self
    {
        if ($this->personalBlockeds->contains($personalBlocked)) {
            $this->personalBlockeds->removeElement($personalBlocked);
            // set the owning side to null (unless already changed)
            if ($personalBlocked->getBlocked() === $this) {
                $personalBlocked->setBlocked(null);
            }
        }

        return $this;
    }
}
