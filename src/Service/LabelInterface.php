<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Constant\LabelConstants as Constant;
use App\Entity\PersonalCategories;
use App\Entity\PersonalDefaultLabels;
use App\Entity\PersonalLabels;
/**
 * This service exists since Label, Default_Label, and Category entities
 * are so similar.
 */

class LabelInterface
{
    // LABEL | DEFAULT_LABEL | CATEGORY
    private $type;
    // Entity is an instantiation of either PersonalLabels,
    // PersonalDefaultLabels, or PersonalCategories.
    private $entity;
    private $em;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $type === Constant::LABEL         ||
        $type === Constant::DEFAULT_LABEL ||
        $type === Constant::CATEGORY
            ? $this->type = $type
            : $this->type = Constant::LABEL;

        $this->setEntity();
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    private function setEntity(): void
    {
        switch ($this->type) {
            case Constant::LABEL:
                $this->entity = PersonalLabels::class;
                break;
            case Constant::DEFAULT_LABEL:
                $this->entity = PersonalDefaultLabels::class;
                break;
            case Constant::CATEGORY:
                $this->entity = PersonalCategories::class;
                break;
        }
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
    public function findPersonalLabel(string $slug, int $id): ?object
    {
        $label = $this->em
                      ->getRepository($this->entity)
                      ->findEmailsBySlug($slug, $id);

        return isset($label[0]) ? $label[0] : null;
    }

    public function getAllLabelsAndCategories(int $id): ?object
    {
        return $this->em->createQuery(
            'SELECT a, b, c FROM App\Entity\Member m LEFT JOIN m.labels a LEFT JOIN m.defaultLabels b LEFT JOIN m.categories c'
        );
    }
}
?>
