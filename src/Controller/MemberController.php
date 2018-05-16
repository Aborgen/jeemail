<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\Dump;

use App\Service\MemberInterface;
use App\Service\PreInsert;
use App\Entity\Label;
use App\Entity\PersonalLabels;
use App\Entity\Member;

class MemberController extends AbstractController
{
    /**
     * @Route("/api/member/details", name="details_show")
     * @Method({ "POST", "GET" })
     */
    public function getDetails(): object
    {
        $member = $this->get('security.token_storage')->getToken()->getUser();
        $repo   = $this->getDoctrine()->getRepository(Member::class);
        $filteredMember = $repo->getFilteredMember($member->getId());
        return new JsonResponse($filteredMember);
    }

    /**
     * @Route("/details/create-label/{label}", name="create_label")
     * @Method({ "GET" })
     */
    public function createLabel(string $label, PreInsert $preInsert): object
    {
        $member  = $this->get('security.token_storage')->getToken()->getUser();
        $manager = $this->getDoctrine()->getManager();

        // Create a new Label entity with information given.
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
        $personalLabel->setVisibility('true');

        // Exact same procedure from above
        $preInsert->setRepo(PersonalLabels::class);
        $dbPLabel = $preInsert->findExactOrReturnOriginalEntity($personalLabel);
        $preInsert->maybePersist();

        // Cleanup
        $preInsert->flush();

        return $this->redirectToRoute('details');
    }
}
