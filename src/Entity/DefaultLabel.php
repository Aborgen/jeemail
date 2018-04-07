<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DefaultLabelRepository")
 * @ORM\Table(name="Default_Label", options={"comment":"These labels are assigned to every member, as opposed to Label"})
 */
class DefaultLabel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="DefaultLabelID", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(name="url_slug", type="string", length=255, unique=true,
     *             options={"comment":"This field will also contain the name of the label. If there are any spaces present, they will be replaced with '-': Default Label -> Default-Label"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PersonalDefaultLabels", mappedBy="defaultLabel")
     */
    private $personalDefaultLabels;

    public function __construct()
    {
        $this->personalDefaultLabels = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|PersonalDefaultLabels[]
     */
    public function getPersonalDefaultLabels(): Collection
    {
        return $this->personalDefaultLabels;
    }

    public function addPersonalDefaultLabel(PersonalDefaultLabels $personalDefaultLabel): self
    {
        if (!$this->personalDefaultLabels->contains($personalDefaultLabel)) {
            $this->personalDefaultLabels[] = $personalDefaultLabel;
            $personalDefaultLabel->setDefaultLabel($this);
        }

        return $this;
    }

    public function removePersonalDefaultLabel(PersonalDefaultLabels $personalDefaultLabel): self
    {
        if ($this->personalDefaultLabels->contains($personalDefaultLabel)) {
            $this->personalDefaultLabels->removeElement($personalDefaultLabel);
            // set the owning side to null (unless already changed)
            if ($personalDefaultLabel->getDefaultLabel() === $this) {
                $personalDefaultLabel->setDefaultLabel(null);
            }
        }

        return $this;
    }
}
