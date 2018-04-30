<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingsRepository")
 * @ORM\Table(name="Settings", options={"comment": "The unique constraint is there since there are a finite number of possible unique combinations."},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="all_columns",columns={
 *         "max_emails_shown",
 *         "max_contacts_shown",
 *         "reply_type",
 *         "display_images",
 *         "button_style",
 *         "ui_display_style"
 *     })
 * })
 */
class Settings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="SettingsID", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", options={"check":"CHECK (max_emails_shown IN ('10', '15', '20', '25', '50', '100'))"})
     */
    private $max_emails_shown;

    /**
     * @ORM\Column(type="text", options={"check":"CHECK (max_contacts_shown IN ('50', '100', '250'))"})
     */
    private $max_contacts_shown;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reply_type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $display_images;

    /**
     * @ORM\Column(type="boolean")
     */
    private $button_style;

    /**
     * @ORM\Column(type="text", options={"check":"CHECK (ui_display_style IN ('comfortable', 'compact', 'cozy'))"})
     */
    private $ui_display_style;

    public function getId()
    {
        return $this->id;
    }

    public function getMaxEmailsShown(): ?string
    {
        return $this->max_emails_shown;
    }

    public function setMaxEmailsShown(string $max_emails_shown): self
    {
        $this->max_emails_shown = $max_emails_shown;

        return $this;
    }

    public function getMaxContactsShown(): ?string
    {
        return $this->max_contacts_shown;
    }

    public function setMaxContactsShown(string $max_contacts_shown): self
    {
        $this->max_contacts_shown = $max_contacts_shown;

        return $this;
    }

    public function getReplyType(): ?string
    {
        return $this->reply_type;
    }

    public function setReplyType(string $reply_type): self
    {
        $this->reply_type = $reply_type;

        return $this;
    }

    public function getDisplayImages(): ?bool
    {
        return $this->display_images;
    }

    public function setDisplayImages(bool $display_images): self
    {
        $this->display_images = $display_images;

        return $this;
    }

    public function getButtonStyle(): ?bool
    {
        return $this->button_style;
    }

    public function setButtonStyle(bool $button_style): self
    {
        $this->button_style = $button_style;

        return $this;
    }

    public function getUiDisplayStyle(): ?string
    {
        return $this->ui_display_style;
    }

    public function setUiDisplayStyle(string $ui_display_style): self
    {
        $this->ui_display_style = $ui_display_style;

        return $this;
    }
}
