<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Constant\LabelConstants as Constant;
use App\Entity\PersonalDefaultLabels;
use App\Entity\PersonalLabels;
/**
 * Since Label and Default_Label entities are so similar --differing only in
 * privilege level required to create-- this service exists
 */

class LabelInterface
{
    // USER_DEFINED | ADMIN_DEFINED
    private $type;
    // Entity is an instantiation of either App\Entity\PersonalLabels or
    // App\Entity\PersonalDefaultLabels.
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
        $type === Constant::USER_DEFINED || $type === Constant::ADMIN_DEFINED
            ? $this->type = $type
            : $this->type = Constant::USER_DEFINED;

        $this->setEntity();
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    private function setEntity(): void
    {
        $this->type === Constant::USER_DEFINED
            ? $this->entity = PersonalLabels::class
            : $this->entity = PersonalDefaultLabels::class;
    }

    /**
 	 * Find member's particular row in either PersonalLabels or PersonalDefaultLabels
 	 *
 	 * @param string $label
     *      Expect $label to be in the form of a slug: My-Label
 	 * @return void
	 */

    public function findPersonalLabel(string $slug, int $id): array
    {
        $label = $this->em
                      ->getRepository($this->entity)
                      ->findEmailsBySlug($slug, $id);

        return $label;
    }
}
?>
