<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

// Entities
use App\Entity\PersonalCategories;
use App\Entity\PersonalDefaultLabels;
use App\Entity\PersonalLabels;

// Services
use App\Service\LabelInterface;

// Constants
use App\Constant\LabelConstants;

class EmailController extends AbstractController
{
    /**
     * @Route("/email", name="email_index")
     * @Method({ "GET" })
     */
    public function index(): object
    {
        return $this->render('email/index.html.twig');
    }

    /**
     * @Route("/email/{defaultLabel}", name="email_show_default_label")
     * @Route("/email/category/{category}", name="email_show_category")
     * @Route("/email/label/{label}", name="email_show_label")
     * @Method({ "GET" })
     */
    public function show(string $defaultLabel = null,
                         string $category = null,
                         string $label = null,
                         LabelInterface $interface): object
    {
        if(isset($defaultLabel)) {
            $interface->setType(LabelConstants::ADMIN_DEFINED);
            $emails = $interface->findPersonalLabel($defaultLabel, 122);
            return $this->render('email/label.html.twig', [
                "emails" => $emails]);
        }

        if(isset($category)) {
            return $this->render('email/category.html.twig', [
                "emails" => $category]);
        }

        if(isset($label)) {
            $interface->setType(LabelConstants::USER_DEFINED);
            $emails = $interface->findPersonalLabel($label, 122);
            return $this->render('email/label.html.twig', [
                "emails" => $emails]);
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
