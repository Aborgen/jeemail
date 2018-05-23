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

    public function findBySlug(int $id, string $slug): ?array
    {
        return $this->createQueryBuilder('re')
                    ->select('re', 'e', 'l')
                    ->addSelect('c', 'c2')
                    ->addSelect('dl', 'dl2')
                    ->addSelect('ls', 'ls2')
                    ->leftJoin('re.email', 'e')
                    ->leftJoin('re.category', 'c')
                    ->leftJoin('c.category', 'c2')
                    ->leftJoin('re.labels', 'l')
                    ->leftJoin('l.defaultLabels', 'dl')
                    ->leftJoin('dl.label', 'dl2')
                    ->leftJoin('l.labels', 'ls')
                    ->leftJoin('ls.label', 'ls2')
                    ->where('re.member = :id')
                    ->andWhere('dl2.slug = :slug OR '.
                               'ls2.slug = :slug OR '.
                               'c2.slug = :slug')
                    ->setParameters(['id' => $id, 'slug' => $slug])
                    ->getQuery()
                    ->getArrayResult();
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
