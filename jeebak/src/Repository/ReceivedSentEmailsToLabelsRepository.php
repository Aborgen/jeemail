<?php

namespace App\Repository;

use App\Entity\ReceivedSentEmailsToLabels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReceivedSentEmailsToLabels|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReceivedSentEmailsToLabels|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReceivedSentEmailsToLabels[]    findAll()
 * @method ReceivedSentEmailsToLabels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceivedSentEmailsToLabelsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReceivedSentEmailsToLabels::class);
    }

//    /**
//     * @return ReceivedSentEmailsToLabels[] Returns an array of ReceivedSentEmailsToLabels objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReceivedSentEmailsToLabels
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
