<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\Dump;

use App\Entity\Member;
use App\Entity\PersonalBlockeds;
use App\Entity\PersonalContacts;

use App\Service\MemberInterface;

class MemberController extends AbstractController
{
    private $member;

    private function getMember(): ?Member
    {
        if (!isset($this->member)) {
            $this->member = $this->get('security.token_storage')
                                 ->getToken()
                                 ->getUser();
        }

        return $this->member;
    }

    /**
     * @Route("/api/member/details", name="member_details")
     * @Method({ "POST", "GET" })
     */
    public function getDetails(MemberInterface $interface): object
    {
        $member         = $this->getMember();
        $filteredMember = $interface->getMember($member->getId());
        return new JsonResponse($filteredMember);
    }

    /**
     * @Route("/api/member/blocked", name="member_blockeds")
     * @Method({ "POST", "GET" })
     */
    public function getBlocked(MemberInterface $interface): object
    {
        $member   = $this->getMember();
        $blockeds = $interface->getBlocked($member->getId());
        return new JsonResponse($blockeds);
    }

    /**
     * @Route("/api/member/contacts", name="member_contacts")
     * @Method({ "POST", "GET" })
     */
    public function getContacts(MemberInterface $interface): object
    {
        $member   = $this->getMember();
        $contacts = $interface->getContacts($member->getId());
        return new JsonResponse($contacts);
    }
}
