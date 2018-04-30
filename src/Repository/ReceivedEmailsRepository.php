<?php

namespace App\Repository;

use App\Entity\ReceivedEmails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReceivedEmails|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReceivedEmails|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReceivedEmails[]    findAll()
 * @method ReceivedEmails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceivedEmailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReceivedEmails::class);
    }

//    /**
//     * @return ReceivedEmails[] Returns an array of ReceivedEmails objects
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
    public function findOneBySomeField($value): ?ReceivedEmails
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
