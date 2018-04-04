<?php

namespace App\Repository;

use App\Entity\PersonalLabels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalDefaultLabels|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalDefaultLabels|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalDefaultLabels[]    findAll()
 * @method PersonalDefaultLabels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalDefaultLabelsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalDefaultLabels::class);
    }

    public function findEmailsBySlug(string $slug): object
    {
        // a = PersonalDefaultLabelTable
        // b = DefaultLabelTable
        // c = ReceivedEmailsTable
        // d = EmailTable
        return $this->createQueryBuilder('a')
                    ->select('c')
                    ->leftJoin('a.defaultLabel', 'b')
                    ->andWhere('b.url_slug' = ':slug')
                    ->andWhere('a.DefaultLabelID' = 'b.DefaultLabelID')
                    ->addParameter(':slug', $slug)
                    ->leftJoin('a.receivedEmails', 'c')
                    ->andWhere('a.PersonalLabelID = c.PersonalLabelID')
                    ->andWhere('a.UserID = :id')
                    ->addParameter(':id', $id)
                    ->leftJoin('c.email', 'd')
                    ->andWhere('c.EmailID', 'd.EmailID')
                    ->orderBy('d.time_sent', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

   /**
    * @return PersonalDefaultLabels[] Returns an array of PersonalDefaultLabels objects
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
    public function findOneBySomeField($value): ?PersonalDefaultLabels
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
