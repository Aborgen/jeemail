<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\PersonalCategories;
use App\Entity\PersonalDefaultLabels;
use App\Entity\PersonalLabels;

/**
 * This service exists since Label, Default_Label, and Category entities
 * are so similar.
 */

class LabelInterface
{
    public const LABEL         = PersonalLabels::class;
    public const DEFAULT_LABEL = PersonalDefaultLabels::class;
    public const CATEGORY      = PersonalCategories::class;
    // Entity is an instantiation of either PersonalLabels,
    // PersonalDefaultLabels, or PersonalCategories.
    private $entity;
    private $em;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    /**
 	 * Find PersonalLabels, PersonalDefaultLabels, or PersonalCategories object
     * given $slug and $id.
 	 *
 	 * @param string $slug
     *      Expect $slug to be in the form of a slug: My-Label
     * @param int $id
     *
 	 * @return object|null
	 */
    public function findEmailsByOrganizer(string $slug, int $id): ?object
    {
        $label = $this->em
                      ->getRepository($this->entity)
                      ->findEmailsBySlug($slug, $id);

        return isset($label[0]) ? $label[0] : null;
    }


    private function getDefaultLabels(int $id): ?array
    {
        return $this->em
                    ->getRepository(SELF::DEFAULT_LABEL)
                    ->findJoinedLabels($id);
    }

    private function getLabels(int $id): ?array
    {
        return $this->em
                    ->getRepository(SELF::LABEL)
                    ->findJoinedLabels($id);
    }

    private function getCategories(int $id): ?array
    {
        return $this->em
                    ->getRepository(SELF::CATEGORY)
                    ->findJoinedCategories($id);
    }

    public function getAllOrganizers(int $id): ?array
    {
        $default    = $this->getDefaultLabels($id);
        $labels     = $this->getLabels($id);
        $categories = $this->getCategories($id);

        return [
            'labels' => [
                'default' => $default,
                'user'    => $labels
            ],
            'categories' => $categories
        ];
    }

    public function refreshLabels(int $id, string $oldObject): object
    {
        $refreshedLabels = $this->getLabels($id);
        return $oldObject['labels']['user'] = $refreshedLabels;
    }
}
?>
