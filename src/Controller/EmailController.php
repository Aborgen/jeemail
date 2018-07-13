<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\VarDumper\Dump;

use App\Entity\Member;
use App\Service\EmailInterface;

/**
 * @Route("/email")
 */
class EmailController extends AbstractController
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
     * @Route("/{defaultLabel}", name="email_show_default_label")
     * @Route("/category/{category}", name="email_show_category")
     * @Route("/label/{label}", name="email_show_label")
     * @Method({ "POST" })
     */
    public function getEmails(string $defaultLabel = null,
                              string $category = null,
                              string $label = null,
                              EmailInterface $interface): JsonResponse
    {
        $member = $this->getMember();
        $id     = $member->getId();
            if(isset($defaultLabel)) {
                $emails = $interface->getEmails($id, $defaultLabel);
            }

            else if(isset($category)) {
                $emails = $interface->getEmails($id, $category);
            }

            else if(isset($label)) {
                $emails = $interface->getEmails($id, $label);
            }

            else {
                $emails = [];
            }

            return new JsonResponse($emails);
    }

    /**
     * @Route("/email/new", name="email_create_new")
     * @Route("/email/forward", name="email_create_forward")
     * @Method({ "POST" })
     */
    public function create(Request $request): object
    {
        return $this->render('email/index.html.twig', ["messages" => ["NEW EMAIL CREATED!"]]);
    }
}
