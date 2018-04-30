<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Several tables in the database are set up in order to avoid inserting
 * duplicate information. Instead, if ALL (aside from primary key) columns of
 * a new row correspond to an already existing row, this class will ensure
 * that that existing row is used.
 *
 * --NOTE-- If an Entity is to use this tool, it MUST have a findExact() method
 *          within its Repository.
 *
 * @method setRepo
 *     The Entity Repository currently being used.
 * @method getEntityExists
 *     Will return true|false depending on if a given Entity was found in db.
 * @method findExactOrReturnOriginalEntity
 *     Takes an Entity and searches the db for its exact duplicate. If there
 *     is an exact duplicate, it will return true. Otherwise, return false.
 * @method maybePersist
 *     This method will persist the stored $entity if $isDuplicateEntity
 *     is true.
 * @method flush
 *     Cleanup the entity manager.
 */

class PreInsert
{
    private $em;
    private $repo;
    private $entity;
    private $isDuplicateEntity = false;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function setRepo(string $repo): void
    {
        $this->repo = $this->em->getRepository($repo);
    }

    public function isDuplicate(): bool
    {
        return $this->isDuplicateEntity;
    }

    public function findExactOrReturnOriginalEntity(object $entity): object
    {
        $databaseEntity = $this->repo->findExact($entity);
        if(isset($databaseEntity)) {
            $this->entity = $databaseEntity;
            $this->isDuplicateEntity = true;
            return $databaseEntity;
        }

        $this->entity = $entity;
        $this->isDuplicateEntity = false;
        return $entity;
    }

    public function maybePersist(): bool
    {
        if(!$this->isDuplicateEntity) {
            $this->em->persist($this->entity);
            return true;
        }

        return false;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
?>
