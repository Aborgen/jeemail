<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[]    findAll()
 * @method Member[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function loadUserByUsername($usernameOrEmail): ?Member
    {
        return $this->createQueryBuilder('u')
                    ->where('u.username = :username OR u.email = :email')
                    ->setParameters([
                        'username' => $usernameOrEmail,
                        'email'    => $usernameOrEmail
                    ])
                    ->getQuery()
                    ->getOneOrNullResult();
    }

//    /**
//     * @return Member[] Returns an array of Member objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Member
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findWithFullName(int $id): ?Member
    {
        return $this->createQueryBuilder('u')
                    ->select('*')
                    ->addSelect('CONCAT_WS(" ", u.first_name, u.last_name) AS full_name')
                    ->andWhere('u.MemberID = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
    public function findAll()
    {
        return $this->findBy(array(), array('username' => 'ASC'));
    }
}
