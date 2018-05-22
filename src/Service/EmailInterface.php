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

    private function getSentEmails(int $id, string $slug): ?array
    {
        return $this->em->getRepository(SELF::SENT)->findBySlug($id, $slug);
    }

    private function getReceivedEmails(int $id, string $slug): ?array
    {
        return $this->em->getRepository(SELF::RECEIVED)->findBySlug($id, $slug);
    }

    private function cleanUp(array &$arr): void
    {
        // TODO: Surely there is a better way of doing this!
        $arr['labels'] = $arr['labels'][0];
        if($arr['labels']['user'] === null) {
            $arr['labels']['user'] = [];
        }
    }

    public function getEmails(int $id, string $slug): array
    {
        // $sent     = $this->getSentEmails($id, $slug);
        $sent = [];
        $received = $this->getReceivedEmails($id, $slug);
        // array_map('self::cleanUp', $received);
        return [$slug => $received + $sent];
    }
}
?>
