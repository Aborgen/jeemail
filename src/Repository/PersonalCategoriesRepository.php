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

    public function findEmailsBySlug(string $slug, int $id): array
    {
        // a = PersonalCategoriesTable
        // b = CategoriesTable
        // c = ReceivedEmailsTable
        // d = EmailTable
        return $this->createQueryBuilder('a')
                    ->leftJoin('a.category', 'b')
                    ->andWhere('b.slug = :slug')
                    ->andWhere('a.member = :id')
                    ->setParameters([':slug' => $slug, ':id' => $id])
                    ->leftJoin('a.receivedEmails', 'c')
                    ->leftJoin('App\Entity\Email', 'd', 'WITH', 'c.email = d.id')
                    ->orderBy('d.timeSent', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
}
