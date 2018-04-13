<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\VarDumper\Dump;

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
        // try {
            if(isset($defaultLabel)) {
                $interface->setType(LabelConstants::DEFAULT_LABEL);
                $personalDefaultLabel = $interface->findPersonalLabel($defaultLabel, 4);
                // if(!isset($personalDefaultLabel)) {
                //     throw new \Exception("$defaultLabel does not exist in the database");
                // }
                dump($personalDefaultLabel);
                return $this->render('email/label.html.twig', [
                    "label" => $personalDefaultLabel]);
            }

            if(isset($category)) {
                $interface->setType(LabelConstants::CATEGORY);
                $personalCategory = $interface->findPersonalLabel($category, 4);
                // if(!isset($personalCategory)) {
                //     throw new \Exception("$category does not exist in the database");
                // }
                return $this->render('email/category.html.twig', [
                    "category" => $personalCategory]);
            }

            if(isset($label)) {
                $interface->setType(LabelConstants::LABEL);
                $personalLabel = $interface->findPersonalLabel($label, 4);
                // if(!isset($personalLabel)) {
                //     throw new \Exception("$label does not exist in the database");
                // }
                // dump($personalLabel);
                return $this->render('email/label.html.twig', [
                    "label" => $personalLabel]);
                // return $this->redirectToRoute('email_index');
            }
        // } catch (\Exception $e) {
        //     return $this->redirectToRoute('email_index');
        // }


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
