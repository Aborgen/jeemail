<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonalDefaultLabelsRepository")
 * @ORM\Table(name="Personal_Default_Labels")
 */
class PersonalDefaultLabels
{
    const JOIN_TABLE = "Default_Label::class";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="PersonalDefaultLabelsID", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="defaultLabels")
     * @ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID", nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="DefaultLabel", inversedBy="personalDefaultLabels")
     * @ORM\JoinColumn(name="DefaultLabelID", referencedColumnName="DefaultLabelID", nullable=false)
     */
    private $defaultLabel;

    /**
     * @ORM\Column(type="boolean", options={"default":1})
     */
    private $visibility;

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

    public function getDefaultLabel(): ?DefaultLabel
    {
        return $this->defaultLabel;
    }

    public function setDefaultLabel(?DefaultLabel $defaultLabel): self
    {
        $this->defaultLabel = $defaultLabel;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }
}
