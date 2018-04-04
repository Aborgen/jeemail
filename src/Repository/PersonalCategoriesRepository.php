<?php

namespace App\Repository;

use App\Entity\PersonalCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalCategories[]    findAll()
 * @method PersonalCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalCategories::class);
    }

//    /**
//     * @return PersonalCategories[] Returns an array of PersonalCategories objects
//     */
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
    public function findOneBySomeField($value): ?PersonalCategories
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
