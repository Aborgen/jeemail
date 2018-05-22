<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\VarDumper\Dump;

// Entities
use App\Entity\PersonalCategories;
use App\Entity\PersonalDefaultLabels;
use App\Entity\PersonalLabels;

// Services
use App\Service\EmailInterface;

/**
 * @Route("/email")
 */
class EmailController extends AbstractController
{
    /**
     * @Route("", name="email_index")
     * @Method({ "GET" })
     */
    public function index(): object
    {
        return $this->render('index.html.twig');
        // return $this->redirectToRoute('email_show_default_label', [
        //     'defaultLabel' => 'Inbox']);
    }

    /**
     * @Route("/{defaultLabel}", name="email_show_default_label")
     * @Route("/category/{category}", name="email_show_category")
     * @Route("/label/{label}", name="email_show_label")
     * @Method({ "GET" })
     */
    public function show(string $defaultLabel = null,
                         string $category = null,
                         string $label = null,
                         EmailInterface $interface): object
    {
        $member = $this->get('security.token_storage')->getToken()->getUser();
        $emailsShown = $member->getSettings()[0]->getMaxEmailsShown();
        $id = $member->getId();
            if(isset($defaultLabel)) {
                $emails = $interface->getEmails($id, $defaultLabel);
                dump($emails);
                return $this->render('email/label.html.twig', [
                    "label"         => $emails,
                    "emailsPerPage" => $emailsShown
                ]);

                return new JsonResponse($emails);
            }

            if(isset($category)) {
                $interface->setEntity($interface::CATEGORY);
                $emails = $interface->findEmailsByOrganizer($category, $id);
                return $this->render('email/category.html.twig', [
                    "category" => $emails
                ]);
            }

            if(isset($label)) {
                $interface->setEntity($interface::LABEL);
                $emails = $interface->findEmailsByOrganizer($label, $id);

                return $this->render('email/label.html.twig', [
                    "label"         => $emails,
                    "emailsPerPage" => $emailsShown
                ]);
            }
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
