<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Member;

class MemberInterface
{
    private $id;
    private $repo;
    function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Member::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

}

?>
