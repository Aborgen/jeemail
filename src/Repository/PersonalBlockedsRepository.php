<?php

namespace App\Repository;

use App\Entity\PersonalBlockeds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalBlockeds|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalBlockeds|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalBlockeds[]    findAll()
 * @method PersonalBlockeds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalBlockedsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalBlockeds::class);
    }

    public function findJoined(int $id): ?array
    {
        return $this->createQueryBuilder('a')
                    ->select('a.id AS id')
                    ->addSelect('b.email')
                    ->leftJoin('a.blocked', 'b')
                    ->where('a.member = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getArrayResult();
    }
//    /**
//     * @return PersonalBlockeds[] Returns an array of PersonalBlockeds objects
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
    public function findOneBySomeField($value): ?PersonalBlockeds
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
