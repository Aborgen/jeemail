<?php
namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use App\Entity\Blocked;
use App\Entity\Contact;
use App\Entity\Label;
use App\Entity\Settings;

/**
 * Certain tables in the database are expected to contain unique entries
 * (either single or multi constraints). The purpose of this event listener
 * is to prevent errors by ensuring this rule is followed. It does this by
 * doing the following:
 *     1) Query the database to see if each entity corresponds to an
 *        already-existing one.
 *     2) If it does, change the proposed entity to the existing one by
 *        utilizing doctrine's UnitOfWork API.
 *     3) Otherwise, the entity is left untouched.
 */
class EnsureNonDuplicateRowEventListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity   = $args->getObject();
        $em       = $args->getEntityManager();
        $uow      = $em->getUnitOfWork();

        if($entity instanceof Blocked) {
            $repo    = $em->getRepository(Blocked::class);
            $blocked = $repo->findOneBy(['email' => $entity->getEmail()]);
            if(isset($blocked)) {
                $entity->setEmail('i\'m dumb:(');
            }
        }

        if($entity instanceof Contact) {
            $repo    = $em->getRepository(Contact::class);
            $contact = $repo->findExact($entity);
            if(isset($contact)) {
                $entity->setName($contact->getName());
            }
        }

        if($entity instanceof Label) {
            $repo  = $em->getRepository(Label::class);
            $label = $repo->findOneBy(['name' => $entity->getName()]);
            if(isset($label)) {
                $label->setName('INTERCEPTED');
                $label->setSlug('INTERCEPTED');
                $metaData = $em->getClassMetaData(get_class($label));
                $uow->recomputeSingleEntityChangeSet($metaData, $label);
            }
        }

        if($entity instanceof Settings) {
            $repo     = $em->getRepository(Settings::class);
            $settings = $repo->findExact($entity);
            if(isset($settings)) {
                $metaData = $em->getClassMetaData(get_class($settings));
                $uow->recomputeSingleEntityChangeSet($metaData, $settings);
            }
        }
    }
}
?>
