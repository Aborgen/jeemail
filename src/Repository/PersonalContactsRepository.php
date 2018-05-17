<?php

namespace App\Repository;

use App\Entity\PersonalContacts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalContacts|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalContacts|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalContacts[]    findAll()
 * @method PersonalContacts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalContactsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalContacts::class);
    }

    public function findJoined(int $id): ?array
    {
        return $this->createQueryBuilder('a')
                    ->select('a.id')
                    ->addSelect('b.name, b.email')
                    ->addSelect('
                        c.type,
                        c.nickname,
                        c.job_title,
                        c.phone,
                        c.birthday,
                        c.relationship,
                        c.website,
                        c.notes')
                    ->leftJoin('a.contact', 'b')
                    ->leftJoin('a.contactDetails', 'c')
                    ->where('a.member = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getArrayResult();
    }
//    /**
//     * @return PersonalContacts[] Returns an array of PersonalContacts objects
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
    public function findOneBySomeField($value): ?PersonalContacts
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
