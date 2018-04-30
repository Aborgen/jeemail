<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IconRepository")
 * @ORM\Table(name="Icon")
 */
class Icon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="IconID", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon_small;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon_medium;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon_large;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Member", mappedBy="icon", cascade={"persist", "remove"})
     */
    private $member;

    public function getId()
    {
        return $this->id;
    }

    public function getIconSmall(): ?string
    {
        return $this->icon_small;
    }

    public function setIconSmall(string $icon_small): self
    {
        $this->icon_small = $icon_small;

        return $this;
    }

    public function getIconMedium(): ?string
    {
        return $this->icon_medium;
    }

    public function setIconMedium(string $icon_medium): self
    {
        $this->icon_medium = $icon_medium;

        return $this;
    }

    public function getIconLarge(): ?string
    {
        return $this->icon_large;
    }

    public function setIconLarge(string $icon_large): self
    {
        $this->icon_large = $icon_large;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;

        // set the owning side of the relation if necessary
        if ($this !== $member->getIcon()) {
            $member->setIcon($this);
        }

        return $this;
    }
}
