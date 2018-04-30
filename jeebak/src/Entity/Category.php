<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(name="Category")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="CategoryID", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true,
     *             options={"comment":"Similar but separate from Default_Label. An email can have both a Category and a Label."})
     */
    private $name;


    /**
     * @ORM\Column(name="url_slug", type="string", length=255, unique=true,
     *             options={"comment":"This field will also contain the name of the category. If there are any spaces present, they will be replaced with '-': Excellent Category -> Excellent-Category"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="PersonalCategories", mappedBy="category")
     */
    private $personalCategories;

    public function __construct()
    {
        $this->personalCategories = new ArrayCollection();
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
     * @return Collection|PersonalCategories[]
     */
    public function getPersonalCategories(): Collection
    {
        return $this->personalCategories;
    }

    public function addPersonalCategory(PersonalCategories $personalCategory): self
    {
        if (!$this->personalCategories->contains($personalCategory)) {
            $this->personalCategories[] = $personalCategory;
            $personalCategory->setCategory($this);
        }

        return $this;
    }

    public function removePersonalCategory(PersonalCategories $personalCategory): self
    {
        if ($this->personalCategories->contains($personalCategory)) {
            $this->personalCategories->removeElement($personalCategory);
            // set the owning side to null (unless already changed)
            if ($personalCategory->getCategory() === $this) {
                $personalCategory->setCategory(null);
            }
        }

        return $this;
    }
}
