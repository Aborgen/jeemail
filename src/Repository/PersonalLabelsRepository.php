<?php

namespace App\Repository;

use App\Entity\PersonalLabels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalLabels|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalLabels|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalLabels[]    findAll()
 * @method PersonalLabels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalLabelsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalLabels::class);
    }

    public function findBySlug(string $slug): object
    {
        return $this->createQueryBuilder('a')
                    ->select('b.name', 'b.url_slug')
                    ->leftJoin(PersonalLabels::JOIN_TABLE, 'b', 'WITH',
                        'a.labelid = b.labelid')
                    ->andWhere('b.url_slug = :slug')
                    ->setParameter('slug', $slug)
                    ->orderBy('b.name', 'DSC')
                    ->getQuery()
                    ->getResult();
    }
   /**
    * @return PersonalLabels[] Returns an array of PersonalLabels objects
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
    public function findOneBySomeField($value): ?PersonalLabels
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
