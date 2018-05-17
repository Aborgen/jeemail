<?php

namespace App\Repository;

use App\Entity\PersonalDefaultLabels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalDefaultLabels|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalDefaultLabels|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalDefaultLabels[]    findAll()
 * @method PersonalDefaultLabels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalDefaultLabelsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalDefaultLabels::class);
    }

    public function findEmailsBySlug(string $slug, int $id): array
    {
        // a = PersonalDefaultLabelTable
        // b = DefaultLabelTable
        // c = ReceivedSentEmailsToLabelsTable
        // d = ReceivedEmailsTable
        // e = EmailTable
        return $this->createQueryBuilder('a')
                    ->leftJoin('a.label', 'b')
                    ->andWhere('b.slug = :slug')
                    ->andWhere('a.member = :id')
                    ->setParameters([':slug' => $slug, ':id' => $id])
                    ->leftJoin('a.sentOrReceivedEmails', 'c')
                    ->leftJoin('App\Entity\ReceivedEmails', 'd', 'WITH', 'c.receivedEmail = d.id')
                    ->leftJoin('App\Entity\Email', 'e', 'WITH', 'd.email = e.id')
                    ->orderBy('e.timeSent', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    public function findJoined(int $id): ?array
    {
        return $this->createQueryBuilder('a')
                    ->select('a.visibility', 'b.name, b.slug')
                    ->leftJoin('a.label', 'b')
                    ->where('a.member = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getArrayResult();
    }

   /**
    * @return PersonalDefaultLabels[] Returns an array of PersonalDefaultLabels objects
    */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PersonalDefaultLabels
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
