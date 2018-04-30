<?php

namespace App\Repository;

use App\Entity\Settings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Settings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Settings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Settings[]    findAll()
 * @method Settings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Settings::class);
    }

    public function findExact(Settings $settings): ?Settings
    {
        return $this->createQueryBuilder('a')
                    ->andWhere('a.max_emails_shown = :emails')
                    ->andWhere('a.max_contacts_shown = :contacts')
                    ->andWhere('a.reply_type = :reply')
                    ->andWhere('a.display_images = :display')
                    ->andWhere('a.button_style = :button')
                    ->andWhere('a.ui_display_style = :ui')
                    ->setParameters([
                        'emails' => $settings->getMaxEmailsShown(),
                        'contacts' => $settings->getMaxContactsShown(),
                        'reply' => $settings->getReplyType(),
                        'display' => $settings->getDisplayImages(),
                        'button' => $settings->getButtonStyle(),
                        'ui' => $settings->getUiDisplayStyle()
                    ])
                    ->getQuery()
                    ->getOneOrNullResult();
    }
//    /**
//     * @return Settings[] Returns an array of Settings objects
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
    public function findOneBySomeField($value): ?Settings
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
