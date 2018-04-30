<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LabelRepository")
 * @ORM\Table(name="Label", options={"comment":"These labels are member defined. If a member tries to define a label that already exists in this table, assign that LabelID"})
 */
class Label
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="LabelID", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(name="url_slug", type="string", length=255, unique=true,
     *             options={"comment":"This field will also contain the name of the label. If there are any spaces present, they will be replaced with '-': My Label -> My-Label"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PersonalLabels", mappedBy="label")
     */
    private $personalLabels;

    public function __construct()
    {
        $this->personalLabels = new ArrayCollection();
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
     * @return Collection|PersonalLabels[]
     */
    public function getPersonalLabels(): Collection
    {
        return $this->personalLabels;
    }

    public function addPersonalLabel(PersonalLabels $personalLabel): self
    {
        if (!$this->personalLabels->contains($personalLabel)) {
            $this->personalLabels[] = $personalLabel;
            $personalLabel->setLabel($this);
        }

        return $this;
    }

    public function removePersonalLabel(PersonalLabels $personalLabel): self
    {
        if ($this->personalLabels->contains($personalLabel)) {
            $this->personalLabels->removeElement($personalLabel);
            // set the owning side to null (unless already changed)
            if ($personalLabel->getLabel() === $this) {
                $personalLabel->setLabel(null);
            }
        }

        return $this;
    }
}
