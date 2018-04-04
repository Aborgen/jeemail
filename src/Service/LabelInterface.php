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
    // Repo is an instantiation of either App\Entity\PersonalLabels or
    // App\Entity\PersonalDefaultLabels.
    private $repo;
    private $em;

    function __construct(EntityManagerInterface $entityManager,
                         int $type = Constant::USER_DEFINED)
    {
        $this->em   = $entityManager;
        $this->type = $type;
        $this->setRepo();
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
    }

    public function getRepo(): string
    {
        return $this->repo;
    }

    private function setRepo(): void
    {
        $this->type === Constant::USER_DEFINED
            ? $this->repo = PersonalLabels::class
            : $this->repo = PersonalDefaultLables::class;
    }

    /**
 	 * Find member's particular row in either PersonalLabels or PersonalDefaultLabels
 	 *
 	 * @param string $label
     *      Expect $label to be in the form of a slug: My-Label
 	 * @return void
	 */

    public function findPersonalLabel(string $slug): object
    {
        $label = $this->em
                      ->getRepository($this->repo)
                      ->findBySlug($slug);

        return $label;
    }
}
?>
