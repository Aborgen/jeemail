<?php

namespace App\Repository;

use App\Entity\Blocked;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Blocked|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blocked|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blocked[]    findAll()
 * @method Blocked[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Blocked::class);
    }

//    /**
//     * @return Blocked[] Returns an array of Blocked objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blocked
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
