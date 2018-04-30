<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Member;

class MemberInterface
{
    private $id;
    private $repo;
    function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Member::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function hydrateMember(): ?object
    {
        $member = $this->repo->find($this->id);
        if(isset($member)) {
            $info = [
                'name' => [
                    'first' => $member->getFirstName(),
                    'last'  => $member->getLastName(),
                    'full'  => $member->getFirstName() ." ". $member->getLastName(),
                ],
                'username' => $member->getUsername(),
                'gender'   => $member->getGender(),
                'birthday' => $member->getBirthday(),
                'address'  => $member->getAddress(),
                'phone'    => $member->getPhone(),
                'email'    => $member->getEmail(),
            ];

            $data = [
                'icon'       => $member->getIcon(),
                'settings'   => $member->getSettings(),
                'contacts'   => $member->getContacts(),
                'blocked'    => $member->getBlockeds(),
                'categories' => $member->getCategories(),
                'labels' => [
                    'label'    => $member->getLabels(),
                    'default'  => $member->getDefaultLabels(),
                ],
                'emails' => [
                    'received' => $member->getReceivedEmails(),
                    'sent'     => $member->getSentEmails()
                ]
            ];

            return (object)['data' => $data, 'info' => $info];
        }

        return null;
    }
}

?>
