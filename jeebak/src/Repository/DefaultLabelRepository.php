<?php

namespace App\Repository;

use App\Entity\DefaultLabel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DefaultLabel|null find($id, $lockMode = null, $lockVersion = null)
 * @method DefaultLabel|null findOneBy(array $criteria, array $orderBy = null)
 * @method DefaultLabel[]    findAll()
 * @method DefaultLabel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DefaultLabelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DefaultLabel::class);
    }

//    /**
//     * @return DefaultLabel[] Returns an array of DefaultLabel objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DefaultLabel
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
