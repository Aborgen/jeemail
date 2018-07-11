<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180703231330 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE Member_MemberID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Sent_Emails_SentEmailsID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Received_Emails_ReceivedEmailsID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Personal_Labels_PersonalLabelsID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Personal_Contacts_PersonalContactsID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Email_EmailID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Personal_Blockeds_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Personal_Categories_PersonalCategoriesID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Label_LabelID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Icon_IconID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Settings_SettingsID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Blocked_BlockedID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Contact_ContactID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Personal_Default_Labels_PersonalDefaultLabelsID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Theme_ThemeID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Category_CategoryID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Received_Sent_Emails_To_Labels_ReceivedSentEmailsToLabelsID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Contact_Details_ContactDetailsID_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE Default_Label_DefaultLabelID_seq INCREMENT BY 1');
        $this->addSql('ALTER TABLE member ADD last_active TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT (now() at TIME ZONE \'utc\') NOT NULL');
        $this->addSql('ALTER TABLE received_emails ADD email_read BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE email RENAME COLUMN time_sent TO timestamp');
        $this->addSql('CREATE UNIQUE INDEX all_columns ON settings (max_emails_shown, max_contacts_shown, reply_type, display_images, button_style, ui_display_style)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER SEQUENCE blocked_blockedid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE contact_contactid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE personal_default_labels_personaldefaultlabelsid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE theme_themeid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE category_categoryid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE received_sent_emails_to_labels_receivedsentemailstolabelsid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE contact_details_contactdetailsid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE default_label_defaultlabelid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE member_memberid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE sent_emails_sentemailsid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE received_emails_receivedemailsid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE personal_labels_personallabelsid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE personal_contacts_personalcontactsid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE email_emailid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE personal_blockeds_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE personal_categories_personalcategoriesid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE label_labelid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE icon_iconid_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE settings_settingsid_seq INCREMENT BY 1');
        $this->addSql('DROP INDEX all_columns');
        $this->addSql('ALTER TABLE Email RENAME COLUMN timestamp TO time_sent');
        $this->addSql('ALTER TABLE Member DROP last_active');
        $this->addSql('ALTER TABLE Received_Emails DROP email_read');
    }
}
