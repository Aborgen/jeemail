<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Member;
use App\Entity\PersonalBlockeds;
use App\Entity\PersonalContacts;

class MemberInterface
{
    public const BLOCKED = PersonalBlockeds::class;
    public const CONTACT = PersonalContacts::class;
    public const MEMBER  = Member::class;
    private $em;
    private $entity;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getBlocked(int $id): ?array
    {
        return $this->em->getRepository(SELF::BLOCKED)->findJoined($id);
    }

    public function getContacts(int $id): ?array
    {
        return $this->em->getRepository(SELF::CONTACT)->findJoined($id);
    }

    public function getMember(int $id): ?array
    {
        return $this->em->getRepository(SELF::MEMBER)->findFilteredMember($id);
    }
}

?>
