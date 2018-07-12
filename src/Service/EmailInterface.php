<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\ReceivedEmails;
use App\Entity\SentEmails;

class EmailInterface
{
    public const SENT     = SentEmails::class;
    public const RECEIVED = ReceivedEmails::class;

    private $em;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    private function getSentEmails(int $id, string $slug): array
    {
        return $this->em->getRepository(SELF::SENT)->findBySlug($id, $slug);
    }

    private function getReceivedEmails(int $id, string $slug): array
    {
        return $this->em->getRepository(SELF::RECEIVED)->findBySlug($id, $slug);
    }

    public function getEmails(int $id, string $slug): array
    {
        $sent     = $this->getSentEmails($id, $slug);
        $received = $this->getReceivedEmails($id, $slug);
        return [$slug => $sent + $received];
    }
}
?>
