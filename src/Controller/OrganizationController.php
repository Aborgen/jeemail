<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\LabelInterface;
use App\Service\PreInsert;
use App\Entity\Member;

class OrganizationController extends AbstractController
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
     * @Route("/api/organization/show", name="label_create")
     * @Method({ "POST" })
     */
     public function createLabel(PreInsert $preInsert, Request $request):boolean
     {
         $member = $this->getMember();

         $manager = $this->getDoctrine()->getManager();

         // Create a new Label entity with information given.
         $label = $request->request->get('label');
         $newLabel = new Label();
         $newLabel->setName($label);
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
     * @Route("/member/organizers", name="label_get")
     * @Method({ "POST" })
     */
    public function getAllLabels(LabelInterface $interface, Request $request)
    {
        $member = $this->getMember();
        $stringOrNull = $request->request->get('organizers');
        $organizers = $interface
                          ->getAllOrganizers($member->getId(), $stringOrNull);
        return new JsonResponse($organizers);
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
