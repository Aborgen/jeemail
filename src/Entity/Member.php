<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 * @ORM\Table(name="Member")
 */
class Member implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="MemberID", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="PersonalContacts", mappedBy="member", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity="PersonalCategories", mappedBy="member", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="PersonalBlockeds", mappedBy="member", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $blockeds;

    /**
     * @ORM\ManyToMany(targetEntity="Settings")
     * @ORM\JoinTable(name="Personal_Settings",
     *     joinColumns={@ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="SettingsID", referencedColumnName="SettingsID")})
     */
    private $settings;

    /**
     * @ORM\OneToMany(targetEntity="PersonalLabels", mappedBy="member", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $labels;

    /**
     * @ORM\OneToMany(targetEntity="PersonalDefaultLabels", mappedBy="member", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $defaultLabels;

    /**
     * @ORM\ManyToMany(targetEntity="Theme", inversedBy="members")
     * @ORM\JoinTable(name="Personal_Themes",
     *     joinColumns={@ORM\JoinColumn(name="MemberID", referencedColumnName="MemberID")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ThemeID", referencedColumnName="ThemeID")})
     */
    private $theme;

    /**
     * @ORM\OneToMany(targetEntity="ReceivedEmails", mappedBy="member", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $receivedEmails;

    /**
     * @ORM\OneToMany(targetEntity="SentEmails", mappedBy="member", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $sentEmails;

    /**
     * @ORM\OneToOne(targetEntity="Icon", inversedBy="member", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="IconID", referencedColumnName="IconID", nullable=true)
     */
    private $icon;

    public function __construct()
    {
        $this->contacts       = new ArrayCollection();
        $this->categories     = new ArrayCollection();
        $this->blockeds       = new ArrayCollection();
        $this->settings       = new ArrayCollection();
        $this->labels         = new ArrayCollection();
        $this->defaultLabels  = new ArrayCollection();
        $this->theme          = new ArrayCollection();
        $this->receivedEmails = new ArrayCollection();
        $this->sentEmails     = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|PersonalContacts[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(PersonalContacts $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setMember($this);
        }

        return $this;
    }

    public function removePersonalContact(PersonalContacts $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getMember() === $this) {
                $contact->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PersonalCategories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(PersonalCategories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setMember($this);
        }

        return $this;
    }

    public function removeCategory(PersonalCategories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getMember() === $this) {
                $category->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PersonalBlockeds[]
     */
    public function getBlockeds(): Collection
    {
        return $this->blockeds;
    }

    public function addBlocked(PersonalBlockeds $blocked): self
    {
        if (!$this->blockeds->contains($blocked)) {
            $this->blockeds[] = $blocked;
            $blocked->setMember($this);
        }

        return $this;
    }

    public function removeBlocked(PersonalBlockeds $blocked): self
    {
        if ($this->blockeds->contains($blocked)) {
            $this->blockeds->removeElement($blocked);
            // set the owning side to null (unless already changed)
            if ($blocked->getMember() === $this) {
                $blocked->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Settings[]
     */
    public function getSettings(): Collection
    {
        return $this->settings;
    }

    public function addSettings(Settings $settings): self
    {
        if (!$this->settings->contains($settings)) {
            $this->settings[] = $settings;
        }

        return $this;
    }

    public function removeSettings(Settings $settings): self
    {
        if ($this->settings->contains($settings)) {
            $this->settings->removeElement($settings);
        }

        return $this;
    }

    /**
     * @return Collection|PersonalLabels[]
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(PersonalLabels $label): self
    {
        if (!$this->labels->contains($label)) {
            $this->labels[] = $label;
            $label->setMember($this);
        }

        return $this;
    }

    public function removeLabel(PersonalLabels $label): self
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
            // set the owning side to null (unless already changed)
            if ($label->getMember() === $this) {
                $label->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PersonalDefaultLabels[]
     */
    public function getDefaultLabels(): Collection
    {
        return $this->defaultLabels;
    }

    public function addDefaultLabel(PersonalDefaultLabels $defaultLabel): self
    {
        if (!$this->defaultLabels->contains($defaultLabel)) {
            $this->defaultLabels[] = $defaultLabel;
            $defaultLabel->setMember($this);
        }

        return $this;
    }

    public function removeDefaultLabel(PersonalDefaultLabels $defaultLabel): self
    {
        if ($this->defaultLabels->contains($defaultLabel)) {
            $this->defaultLabels->removeElement($defaultLabel);
            // set the owning side to null (unless already changed)
            if ($defaultLabel->getMember() === $this) {
                $defaultLabel->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Theme[]
     */
    public function getTheme(): Collection
    {
        return $this->theme;
    }

    public function addTheme(Theme $theme): self
    {
        if (!$this->theme->contains($theme)) {
            $this->theme[] = $theme;
        }

        return $this;
    }

    public function removeTheme(Theme $theme): self
    {
        if ($this->theme->contains($theme)) {
            $this->theme->removeElement($theme);
        }

        return $this;
    }

    /**
     * @return Collection|ReceivedEmails[]
     */
    public function getReceivedEmails(): Collection
    {
        return $this->receivedEmails;
    }

    public function addReceivedEmail(ReceivedEmails $receivedEmail): self
    {
        if (!$this->receivedEmails->contains($receivedEmail)) {
            $this->receivedEmails[] = $receivedEmail;
            $receivedEmail->setMember($this);
        }

        return $this;
    }

    public function removeReceivedEmail(ReceivedEmails $receivedEmail): self
    {
        if ($this->receivedEmails->contains($receivedEmail)) {
            $this->receivedEmails->removeElement($receivedEmail);
            // set the owning side to null (unless already changed)
            if ($receivedEmail->getMember() === $this) {
                $receivedEmail->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SentEmails[]
     */
    public function getSentEmails(): Collection
    {
        return $this->sentEmails;
    }

    public function addSentEmail(SentEmails $sentEmail): self
    {
        if (!$this->sentEmails->contains($sentEmail)) {
            $this->sentEmails[] = $sentEmail;
            $sentEmail->setMember($this);
        }

        return $this;
    }

    public function removeSentEmail(SentEmails $sentEmail): self
    {
        if ($this->sentEmails->contains($sentEmail)) {
            $this->sentEmails->removeElement($sentEmail);
            // set the owning side to null (unless already changed)
            if ($sentEmail->getMember() === $this) {
                $sentEmail->setMember(null);
            }
        }

        return $this;
    }

    public function getIcon(): ?Icon
    {
        return $this->icon;
    }

    public function setIcon(Icon $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Mandatory because we are implementing UserInterface
     */

    public function getSalt()
    {
        return null;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }

    public function serialize(): string
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    public function unserialize($serialized): void
    {
        list (
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}
