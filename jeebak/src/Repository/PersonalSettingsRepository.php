<?php

namespace App\Repository;

use App\Entity\PersonalSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalSettings[]    findAll()
 * @method PersonalSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalSettingsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalSettings::class);
    }

//    /**
//     * @return PersonalSettings[] Returns an array of PersonalSettings objects
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
    public function findOneBySomeField($value): ?PersonalSettings
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
