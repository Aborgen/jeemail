<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Nelmio\Alice\Loader\NativeLoader;
// Entities
use App\Entity\Blocked;
use App\Entity\PersonalBlocked;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\ContactDetails;
use App\Entity\DefaultLabel;
use App\Entity\Email;
use App\Entity\Icon;
use App\Entity\Label;
use App\Entity\Member;
use App\Entity\ReceivedEmails;
use App\Entity\SentEmails;
use App\Entity\Settings;
use App\Entity\Theme;

class EmailFixtures extends Fixture
{
    public function load(ObjectManager $manager, int $count = 1): void
    {
        // // Create Member
        // for ($i=0; $i < $count; $i++) {
        //     $member = new Member();
        //     $member->setFirstName();
        //     $member->setLastName();
        //     $member->setGender();
        //     $member->setBirthday();
        //     $member->setAddress();
        //     $member->setPhone();
        //     $member->setMembername();
        //     $member->setEmail();
        //     $member->setPassword();
        //     // Set icon, settings, and theme to default
        //     $member->setIcon(0);
        //     $member->setSettings(0);
        //     $member->setTheme(0);
        //     // TODO: Maybe Settings and Theme is not many-to-many?
        //
        //     // Create Blocked
        //     for ($i=0; $i < rand(0, 5); $i++) {
        //         $blocked = new Blocked();
        //         $blocked->setEmail();
        //         $member->addBlocked($blocked);
        //     }
        //
        //     // Create Contact
        //     for ($i=0; $i < rand(0, 500); $i++) {
        //         $contact = new Contact();
        //         $contact->setName();
        //         $contact->setEmail();
        //         $member->addContact($contact);
        //     }
        //
        //     // Create Labels
        //     for ($i=0; $i < rand(0, 10); $i++) {
        //         $label = new Label();
        //         $label->setName();
        //         $member->addLabel($label);
        //     }
        //
        //     $manager->persist($member);
        // }
        $loader = new NativeLoader();
        for ($i=0; $i < 3; $i++) {
            $objectSet = $loader->loadFile(__DIR__.'/EmailFixtures.yaml')
                                ->getObjects();
            foreach($objectSet as $object) {
                $manager->persist($object);
            }

            $manager->flush();
        }
    }
}
