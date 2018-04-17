<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Service\MemberInterface;
class MemberController extends Controller
{
    /**
     * @Route("/details", name="details")
     */
    public function index(MemberInterface $interface): object
    {
        $id = 23;
        $interface->setId($id);
        $member = $interface->hydrateMember();
        return $this->render('member/index.html.twig', ['member' => $member]);
    }
}
