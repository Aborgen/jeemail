<?php

namespace App\Repository;

use App\Entity\SentEmails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SentEmails|null find($id, $lockMode = null, $lockVersion = null)
 * @method SentEmails|null findOneBy(array $criteria, array $orderBy = null)
 * @method SentEmails[]    findAll()
 * @method SentEmails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SentEmailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SentEmails::class);
    }

//    /**
//     * @return SentEmails[] Returns an array of SentEmails objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SentEmails
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
