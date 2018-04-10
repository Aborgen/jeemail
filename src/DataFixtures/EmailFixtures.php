<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

// Entities
use App\Entity\Member;


class EmailFixtures extends Fixture
{
    // private function setDefaultMembers(ObjectManager $manager): array
    // {
    //     $defaultMembers = [
    //         [
    //             'firstName' => 'noreply',
    //             'lastName'  => 'noreply',
    //             'username'  => 'noreply',
    //             'email'     => 'noreply',
    //             'password'  => 'noreply'
    //         ],
    //         [
    //             'firstName' => 'admin',
    //             'lastName'  => 'admin',
    //             'username'  => 'admin',
    //             'email'     => 'admin',
    //             'password'  => 'admin'
    //         ],
    //         [
    //             'firstName' => 'Greg',
    //             'lastName'  => 'Spleen',
    //             'username'  => 'The Spleen',
    //             'email'     => 'greg.spleen',
    //             'password'  => 'spleeeeen'
    //         ]
    //     ];
    //     $memberObjects = [];
    //     foreach ($defaultMembers as $defaultMember) {
    //         $member = new Member();
    //         $member->setFirstName($defaultMember['firstName'])
    //                ->setLastName($defaultMember['lastName'])
    //                ->setUsername($defaultMember['username'])
    //                ->setEmail($defaultMember['email'])
    //                ->setPassword($defaultMember['password']);
    //
    //         $manager->persist($member);
    //         $memberObjects[] = $member;
    //         $manager->flush();
    //     }
    //
    //     return $memberObjects;
    // }

    public function load(ObjectManager $manager, int $count = 1): void
    {
        $loader = new NativeLoader();
        for ($i=0; $i < $count; $i++) {
            // $data = [];
            // if($i === 0) {
            //     $data = $this->setDefaultMembers($manager);
            // }

            $objects = $loader->loadFile(__DIR__.'/EmailFixtures.yaml')
                              ->getObjects();
            foreach($objects as $object) {
                $manager->persist($object);
            }

            $manager->flush();
        }
    }
}
