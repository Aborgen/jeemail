<?php
namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Security;

use App\Entity\Member;

class ActivityListener
{
    private $em;
    private $security;
    private $member;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em       = $em;
        $this->security = $security;
        $this->setMember();
    }

    private function setMember(): void
    {
        $member = $this->security->getToken();
        if(isset($member)) {
            $member->getUser();
        }

        $this->member = $member;

        return;
    }
    // Member | Symfony\Component\Security\Core\Authentication\Token\AnonymousToken
    private function getMember()
    {
        return $this->member;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        // Check that the current request is a "MASTER_REQUEST"
        // Ignore any sub-request
        if(!$event->isMasterRequest()) {
            return;
        }

        $member = $this->getMember();
        if($member instanceof Member) {
            if(!$member->isActiveNow()) {
                $member->setLastActive(new \DateTime());
                $this->em->flush($member);
            }
        }
    }
}
