<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\LabelInterface;
use App\Service\PreInsert;

class OrganizationController extends AbstractController
{
    /**
     * @Route("/api/organization/show", name="label_create")
     * @Method({ "POST" })
     */
     public function createLabel(PreInsert $preInsert, Request $request):boolean
     {
         $member = $this->get('security.token_storage')->getToken()->getUser();
         if(!isset($member)) {
             return false;
         }

         $manager = $this->getDoctrine()->getManager();

         // Create a new Label entity with information given.
         $label = $request->request->get('label');
         $newLabel = new Label();
         $newLabel->setName($label);
         // TODO: sluggify service
         $newLabel->setSlug($label);

         // Next, use PreInsert to query the database to determine whether
         // the new Label entity is a duplicate. If it is, it will return
         // the pre-existing entity to be used in PersonalLabels
         $preInsert->setRepo(Label::class);

         // Will either be the new Label or the pre-existing Label
         $dbLabel = $preInsert->findExactOrReturnOriginalEntity($newLabel);
         $preInsert->maybePersist();

         // Create a new PersonalLabels entity with Member and Label entities
         $personalLabel = new PersonalLabels();
         $personalLabel->setMember($member);
         $personalLabel->setLabel($dbLabel);
         $personalLabel->setVisibility(true);

         // Exact same procedure from above
         $preInsert->setRepo(PersonalLabels::class);
         $dbPLabel =
            $preInsert->findExactOrReturnOriginalEntity($personalLabel);
         $preInsert->maybePersist();

         // Cleanup
         $preInsert->flush();

         return true;
     }

    /**
     * @Route("/api/member/organizers", name="label_get")
     * @Method({ "POST", "GET" })
     */
    public function getAllLabels(LabelInterface $interface)
    {
        $member = $this->get('security.token_storage')->getToken()->getUser();
        if(!isset($member)) {
            return false;
        }


    }

    /**
     * @Route("/api/label/edit", name="label_edit")
     * @Method({ "POST" })
     */
    public function editLabel()
    {
        return $this->render('organization/index.html.twig', [
            'controller_name' => 'OrganizationController',
        ]);
    }

    /**
     * @Route("/api/label/delete", name="label_delete")
     * @Method({ "POST" })
     */
    public function deleteLabel()
    {
        return $this->render('organization/index.html.twig', [
            'controller_name' => 'OrganizationController',
        ]);
    }
}
