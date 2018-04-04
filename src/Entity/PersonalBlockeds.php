<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonalBlockedsRepository")
 * @ORM\Table(name="Personal_Blockeds")
 */
class PersonalBlockeds
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="blockeds")
     * @ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID", nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Blocked", inversedBy="personalBlockeds")
     * @ORM\JoinColumn(name="BlockedID", referencedColumnName="BlockedID", nullable=false)
     */
    private $blocked;

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

    public function getBlocked(): ?Blocked
    {
        return $this->blocked;
    }

    public function setBlocked(?Blocked $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }
}
