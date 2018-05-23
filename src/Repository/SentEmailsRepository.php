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

    public function findBySlug(int $id, string $slug): ?array
    {
        return $this->createQueryBuilder('se')
                    ->select('se', 'e', 'l')
                    ->addSelect('dl', 'dl2')
                    ->addSelect('ls', 'ls2')
                    ->leftJoin('se.email', 'e')
                    ->leftJoin('se.category', 'c')
                    ->leftJoin('c.category', 'c2')
                    ->leftJoin('se.labels', 'l')
                    ->leftJoin('l.defaultLabels', 'dl')
                    ->leftJoin('dl.label', 'dl2')
                    ->leftJoin('l.labels', 'ls')
                    ->leftJoin('ls.label', 'ls2')
                    ->where('se.member = :id')
                    ->andWhere('dl2.slug = :slug OR '.
                               'ls2.slug = :slug OR '.
                               'c2.slug = :slug')
                    ->setParameters(['id' => $id, 'slug' => $slug])
                    ->getQuery()
                    ->getArrayResult();
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
