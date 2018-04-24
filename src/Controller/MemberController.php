<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\MemberInterface;
class MemberController extends Controller
{
    /**
     * @Route("/details", name="details")
     */
    public function index(MemberInterface $interface, Request $request): object
    {
        $id = $request->get('id');
        if(!isset($id)){
            $id = -100;
        }

        $interface->setId($id);
        $member = $interface->hydrateMember();
        return $this->render('member/index.html.twig', ['member' => $member]);
    }
}
