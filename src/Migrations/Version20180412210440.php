<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180412210440 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE Member_MemberID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Sent_Emails_SentEmailsID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Received_Emails_ReceivedEmailsID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Personal_Labels_PersonalLabelsID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Personal_Contacts_PersonalContactsID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Email_EmailID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Personal_Blockeds_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Personal_Categories_PersonalCategoriesID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Label_LabelID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Icon_IconID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Settings_SettingsID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Blocked_BlockedID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Contact_ContactID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Personal_Default_Labels_PersonalDefaultLabelsID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Theme_ThemeID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Category_CategoryID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Received_Sent_Emails_To_Labels_ReceivedSentEmailsToLabelsID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Contact_Details_ContactDetailsID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Default_Label_DefaultLabelID_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE Member (MemberID INT NOT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, gender VARCHAR(64) DEFAULT NULL, birthday VARCHAR(64) DEFAULT NULL, address VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, username VARCHAR(64) NOT NULL, email VARCHAR(64) NOT NULL, password VARCHAR(255) NOT NULL, IconID INT DEFAULT NULL, PRIMARY KEY(MemberID))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7748FF4EE7927C74 ON Member (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7748FF4E36018720 ON Member (IconID)');
        $this->addSql('CREATE TABLE Personal_Settings (MemberID INT NOT NULL, SettingsID INT NOT NULL, PRIMARY KEY(MemberID, SettingsID))');
        $this->addSql('CREATE INDEX IDX_9EAF2E20522B9974 ON Personal_Settings (MemberID)');
        $this->addSql('CREATE INDEX IDX_9EAF2E203ED5054C ON Personal_Settings (SettingsID)');
        $this->addSql('CREATE TABLE Personal_Themes (MemberID INT NOT NULL, ThemeID INT NOT NULL, PRIMARY KEY(MemberID, ThemeID))');
        $this->addSql('CREATE INDEX IDX_6EA7E561522B9974 ON Personal_Themes (MemberID)');
        $this->addSql('CREATE INDEX IDX_6EA7E56128D93000 ON Personal_Themes (ThemeID)');
        $this->addSql('CREATE TABLE Sent_Emails (SentEmailsID INT NOT NULL, important BOOLEAN NOT NULL, starred BOOLEAN NOT NULL, MemberID INT NOT NULL, EmailID INT NOT NULL, PersonalCategoriesID INT NOT NULL, PRIMARY KEY(SentEmailsID))');
        $this->addSql('CREATE INDEX IDX_2EDCD538522B9974 ON Sent_Emails (MemberID)');
        $this->addSql('CREATE INDEX IDX_2EDCD538CC8E3CD1 ON Sent_Emails (EmailID)');
        $this->addSql('CREATE INDEX IDX_2EDCD538A9AE1681 ON Sent_Emails (PersonalCategoriesID)');
        $this->addSql('CREATE TABLE Received_Emails (ReceivedEmailsID INT NOT NULL, important BOOLEAN DEFAULT \'false\' NOT NULL, starred BOOLEAN DEFAULT \'false\' NOT NULL, MemberID INT NOT NULL, EmailID INT NOT NULL, PersonalCategoriesID INT DEFAULT NULL, PRIMARY KEY(ReceivedEmailsID))');
        $this->addSql('CREATE INDEX IDX_4793F8D9522B9974 ON Received_Emails (MemberID)');
        $this->addSql('CREATE INDEX IDX_4793F8D9CC8E3CD1 ON Received_Emails (EmailID)');
        $this->addSql('CREATE INDEX IDX_4793F8D9A9AE1681 ON Received_Emails (PersonalCategoriesID)');
        $this->addSql('CREATE TABLE Personal_Labels (PersonalLabelsID INT NOT NULL, visibility BOOLEAN DEFAULT \'true\' NOT NULL, MemberID INT NOT NULL, LabelID INT NOT NULL, PRIMARY KEY(PersonalLabelsID))');
        $this->addSql('CREATE INDEX IDX_CE34D5AE522B9974 ON Personal_Labels (MemberID)');
        $this->addSql('CREATE INDEX IDX_CE34D5AE23A27C11 ON Personal_Labels (LabelID)');
        $this->addSql('CREATE TABLE Personal_Contacts (PersonalContactsID INT NOT NULL, MemberID INT NOT NULL, ContactID INT NOT NULL, ContactDetailsID INT NOT NULL, PRIMARY KEY(PersonalContactsID))');
        $this->addSql('CREATE INDEX IDX_48AA9B96522B9974 ON Personal_Contacts (MemberID)');
        $this->addSql('CREATE INDEX IDX_48AA9B96CDD4564D ON Personal_Contacts (ContactID)');
        $this->addSql('CREATE INDEX IDX_48AA9B966646987C ON Personal_Contacts (ContactDetailsID)');
        $this->addSql('CREATE TABLE Email (EmailID INT NOT NULL, reply_to_email VARCHAR(64) NOT NULL, subject VARCHAR(128) DEFAULT NULL, body TEXT DEFAULT NULL, time_sent TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, MemberID INT NOT NULL, PRIMARY KEY(EmailID))');
        $this->addSql('CREATE INDEX IDX_26535370522B9974 ON Email (MemberID)');
        $this->addSql('CREATE TABLE Personal_Blockeds (id INT NOT NULL, MemberID INT NOT NULL, BlockedID INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D869725522B9974 ON Personal_Blockeds (MemberID)');
        $this->addSql('CREATE INDEX IDX_8D8697259F799877 ON Personal_Blockeds (BlockedID)');
        $this->addSql('CREATE TABLE Personal_Categories (PersonalCategoriesID INT NOT NULL, visibility BOOLEAN DEFAULT \'true\' NOT NULL, MemberID INT NOT NULL, CategoryID INT NOT NULL, PRIMARY KEY(PersonalCategoriesID))');
        $this->addSql('CREATE INDEX IDX_13FA940C522B9974 ON Personal_Categories (MemberID)');
        $this->addSql('CREATE INDEX IDX_13FA940CE8042869 ON Personal_Categories (CategoryID)');
        $this->addSql('CREATE TABLE Label (LabelID INT NOT NULL, name VARCHAR(255) NOT NULL, url_slug VARCHAR(255) NOT NULL, PRIMARY KEY(LabelID))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CF667FEC5E237E06 ON Label (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CF667FEC86C952DA ON Label (url_slug)');
        $this->addSql('COMMENT ON COLUMN Label.url_slug IS \'This field will also contain the name of the label. If there are any spaces present, they will be replaced with \'\'-\'\': My Label -> My-Label\'');
        $this->addSql('CREATE TABLE Icon (IconID INT NOT NULL, icon_small VARCHAR(255) NOT NULL, icon_medium VARCHAR(255) NOT NULL, icon_large VARCHAR(255) NOT NULL, PRIMARY KEY(IconID))');
        $this->addSql('CREATE TABLE Settings (SettingsID INT NOT NULL, max_emails_shown TEXT NOT NULL CHECK (max_emails_shown IN (\'10\', \'15\', \'20\', \'25\', \'50\', \'100\')), max_contacts_shown TEXT NOT NULL CHECK (max_contacts_shown IN (\'50\', \'100\', \'250\')), reply_type BOOLEAN NOT NULL, display_images BOOLEAN NOT NULL, button_style BOOLEAN NOT NULL, ui_display_style TEXT NOT NULL CHECK (ui_display_style IN (\'comfortable\', \'compact\', \'cozy\')), PRIMARY KEY(SettingsID))');
        $this->addSql('CREATE TABLE Blocked (BlockedID INT NOT NULL, email VARCHAR(64) NOT NULL, PRIMARY KEY(BlockedID))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_15E8D21CE7927C74 ON Blocked (email)');
        $this->addSql('CREATE TABLE Contact (ContactID INT NOT NULL, name VARCHAR(64) NOT NULL, email VARCHAR(64) NOT NULL, PRIMARY KEY(ContactID))');
        $this->addSql('CREATE UNIQUE INDEX name__email ON Contact (name, email)');
        $this->addSql('COMMENT ON COLUMN Contact.email IS \'Should be able to query for Member using email.\'');
        $this->addSql('CREATE TABLE Personal_Default_Labels (PersonalDefaultLabelsID INT NOT NULL, visibility BOOLEAN DEFAULT \'true\' NOT NULL, MemberID INT NOT NULL, DefaultLabelID INT NOT NULL, PRIMARY KEY(PersonalDefaultLabelsID))');
        $this->addSql('CREATE INDEX IDX_FC15AE5B522B9974 ON Personal_Default_Labels (MemberID)');
        $this->addSql('CREATE INDEX IDX_FC15AE5BF0A4E819 ON Personal_Default_Labels (DefaultLabelID)');
        $this->addSql('CREATE TABLE Theme (ThemeID INT NOT NULL, name VARCHAR(64) NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(ThemeID))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_56B4C80C5E237E06 ON Theme (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_56B4C80C5E9E89CB ON Theme (location)');
        $this->addSql('CREATE TABLE Category (CategoryID INT NOT NULL, name VARCHAR(255) NOT NULL, url_slug VARCHAR(255) NOT NULL, PRIMARY KEY(CategoryID))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF3A7B975E237E06 ON Category (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF3A7B9786C952DA ON Category (url_slug)');
        $this->addSql('COMMENT ON COLUMN Category.name IS \'Similar but separate from Default_Label. An email can have both a Category and a Label.\'');
        $this->addSql('COMMENT ON COLUMN Category.url_slug IS \'This field will also contain the name of the category. If there are any spaces present, they will be replaced with \'\'-\'\': Excellent Category -> Excellent-Category\'');
        $this->addSql('CREATE TABLE Received_Sent_Emails_To_Labels (ReceivedSentEmailsToLabelsID INT NOT NULL, ReceivedEmailsID INT DEFAULT NULL, SentEmailsID INT DEFAULT NULL, PersonalDefaultLabelsID INT DEFAULT NULL, PersonalLabelsID INT DEFAULT NULL, PRIMARY KEY(ReceivedSentEmailsToLabelsID))');
        $this->addSql('CREATE INDEX IDX_74863742F6D8828 ON Received_Sent_Emails_To_Labels (ReceivedEmailsID)');
        $this->addSql('CREATE INDEX IDX_74863742D80915EB ON Received_Sent_Emails_To_Labels (SentEmailsID)');
        $this->addSql('CREATE INDEX IDX_74863742E35EFF5F ON Received_Sent_Emails_To_Labels (PersonalDefaultLabelsID)');
        $this->addSql('CREATE INDEX IDX_74863742B52265AA ON Received_Sent_Emails_To_Labels (PersonalLabelsID)');
        $this->addSql('CREATE TABLE Contact_Details (ContactDetailsID INT NOT NULL, type TEXT DEFAULT NULL CHECK (type IN (\'business\', \'personal\')), nickname VARCHAR(64) DEFAULT NULL, company VARCHAR(64) DEFAULT NULL, job_title VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, address VARCHAR(64) DEFAULT NULL, birthday VARCHAR(64) DEFAULT NULL, relationship VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, notes TEXT DEFAULT NULL, PRIMARY KEY(ContactDetailsID))');
        $this->addSql('CREATE TABLE Default_Label (DefaultLabelID INT NOT NULL, name VARCHAR(255) NOT NULL, url_slug VARCHAR(255) NOT NULL, PRIMARY KEY(DefaultLabelID))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_953E60175E237E06 ON Default_Label (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_953E601786C952DA ON Default_Label (url_slug)');
        $this->addSql('COMMENT ON COLUMN Default_Label.url_slug IS \'This field will also contain the name of the label. If there are any spaces present, they will be replaced with \'\'-\'\': Default Label -> Default-Label\'');
        $this->addSql('ALTER TABLE Member ADD CONSTRAINT FK_7748FF4E36018720 FOREIGN KEY (IconID) REFERENCES Icon (IconID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Settings ADD CONSTRAINT FK_9EAF2E20522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Settings ADD CONSTRAINT FK_9EAF2E203ED5054C FOREIGN KEY (SettingsID) REFERENCES Settings (SettingsID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Themes ADD CONSTRAINT FK_6EA7E561522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Themes ADD CONSTRAINT FK_6EA7E56128D93000 FOREIGN KEY (ThemeID) REFERENCES Theme (ThemeID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Sent_Emails ADD CONSTRAINT FK_2EDCD538522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Sent_Emails ADD CONSTRAINT FK_2EDCD538CC8E3CD1 FOREIGN KEY (EmailID) REFERENCES Email (EmailID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Sent_Emails ADD CONSTRAINT FK_2EDCD538A9AE1681 FOREIGN KEY (PersonalCategoriesID) REFERENCES Personal_Categories (PersonalCategoriesID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Received_Emails ADD CONSTRAINT FK_4793F8D9522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Received_Emails ADD CONSTRAINT FK_4793F8D9CC8E3CD1 FOREIGN KEY (EmailID) REFERENCES Email (EmailID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Received_Emails ADD CONSTRAINT FK_4793F8D9A9AE1681 FOREIGN KEY (PersonalCategoriesID) REFERENCES Personal_Categories (PersonalCategoriesID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Labels ADD CONSTRAINT FK_CE34D5AE522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Labels ADD CONSTRAINT FK_CE34D5AE23A27C11 FOREIGN KEY (LabelID) REFERENCES Label (LabelID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Contacts ADD CONSTRAINT FK_48AA9B96522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Contacts ADD CONSTRAINT FK_48AA9B96CDD4564D FOREIGN KEY (ContactID) REFERENCES Contact (ContactID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Contacts ADD CONSTRAINT FK_48AA9B966646987C FOREIGN KEY (ContactDetailsID) REFERENCES Contact_Details (ContactDetailsID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Email ADD CONSTRAINT FK_26535370522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Blockeds ADD CONSTRAINT FK_8D869725522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Blockeds ADD CONSTRAINT FK_8D8697259F799877 FOREIGN KEY (BlockedID) REFERENCES Blocked (BlockedID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Categories ADD CONSTRAINT FK_13FA940C522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Categories ADD CONSTRAINT FK_13FA940CE8042869 FOREIGN KEY (CategoryID) REFERENCES Category (CategoryID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Default_Labels ADD CONSTRAINT FK_FC15AE5B522B9974 FOREIGN KEY (MemberID) REFERENCES Member (MemberID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Personal_Default_Labels ADD CONSTRAINT FK_FC15AE5BF0A4E819 FOREIGN KEY (DefaultLabelID) REFERENCES Default_Label (DefaultLabelID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels ADD CONSTRAINT FK_74863742F6D8828 FOREIGN KEY (ReceivedEmailsID) REFERENCES Received_Emails (ReceivedEmailsID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels ADD CONSTRAINT FK_74863742D80915EB FOREIGN KEY (SentEmailsID) REFERENCES Sent_Emails (SentEmailsID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels ADD CONSTRAINT FK_74863742E35EFF5F FOREIGN KEY (PersonalDefaultLabelsID) REFERENCES Personal_Default_Labels (PersonalDefaultLabelsID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels ADD CONSTRAINT FK_74863742B52265AA FOREIGN KEY (PersonalLabelsID) REFERENCES Personal_Labels (PersonalLabelsID) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels ADD CONSTRAINT CHK_FORCED_NULL CHECK (((ReceivedEmailsID IS NOT NULL OR SentEmailsID IS NOT NULL) AND (ReceivedEmailsID IS NULL OR SentEmailsID IS NULL)) AND ((PersonalDefaultLabelsID IS NOT NULL OR PersonalLabelsID IS NOT NULL) AND (PersonalDefaultLabelsID IS NULL OR PersonalLabelsID IS NULL)))');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('ALTER TABLE Personal_Settings DROP CONSTRAINT FK_9EAF2E20522B9974');
        $this->addSql('ALTER TABLE Personal_Themes DROP CONSTRAINT FK_6EA7E561522B9974');
        $this->addSql('ALTER TABLE Sent_Emails DROP CONSTRAINT FK_2EDCD538522B9974');
        $this->addSql('ALTER TABLE Received_Emails DROP CONSTRAINT FK_4793F8D9522B9974');
        $this->addSql('ALTER TABLE Personal_Labels DROP CONSTRAINT FK_CE34D5AE522B9974');
        $this->addSql('ALTER TABLE Personal_Contacts DROP CONSTRAINT FK_48AA9B96522B9974');
        $this->addSql('ALTER TABLE Email DROP CONSTRAINT FK_26535370522B9974');
        $this->addSql('ALTER TABLE Personal_Blockeds DROP CONSTRAINT FK_8D869725522B9974');
        $this->addSql('ALTER TABLE Personal_Categories DROP CONSTRAINT FK_13FA940C522B9974');
        $this->addSql('ALTER TABLE Personal_Default_Labels DROP CONSTRAINT FK_FC15AE5B522B9974');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels DROP CONSTRAINT FK_74863742D80915EB');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels DROP CONSTRAINT FK_74863742F6D8828');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels DROP CONSTRAINT FK_74863742B52265AA');
        $this->addSql('ALTER TABLE Sent_Emails DROP CONSTRAINT FK_2EDCD538CC8E3CD1');
        $this->addSql('ALTER TABLE Received_Emails DROP CONSTRAINT FK_4793F8D9CC8E3CD1');
        $this->addSql('ALTER TABLE Sent_Emails DROP CONSTRAINT FK_2EDCD538A9AE1681');
        $this->addSql('ALTER TABLE Received_Emails DROP CONSTRAINT FK_4793F8D9A9AE1681');
        $this->addSql('ALTER TABLE Personal_Labels DROP CONSTRAINT FK_CE34D5AE23A27C11');
        $this->addSql('ALTER TABLE Member DROP CONSTRAINT FK_7748FF4E36018720');
        $this->addSql('ALTER TABLE Personal_Settings DROP CONSTRAINT FK_9EAF2E203ED5054C');
        $this->addSql('ALTER TABLE Personal_Blockeds DROP CONSTRAINT FK_8D8697259F799877');
        $this->addSql('ALTER TABLE Personal_Contacts DROP CONSTRAINT FK_48AA9B96CDD4564D');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels DROP CONSTRAINT FK_74863742E35EFF5F');
        $this->addSql('ALTER TABLE Received_Sent_Emails_To_Labels DROP CONSTRAINT CHK_FORCED_NULL');
        $this->addSql('ALTER TABLE Personal_Themes DROP CONSTRAINT FK_6EA7E56128D93000');
        $this->addSql('ALTER TABLE Personal_Categories DROP CONSTRAINT FK_13FA940CE8042869');
        $this->addSql('ALTER TABLE Personal_Contacts DROP CONSTRAINT FK_48AA9B966646987C');
        $this->addSql('ALTER TABLE Personal_Default_Labels DROP CONSTRAINT FK_FC15AE5BF0A4E819');
        $this->addSql('DROP SEQUENCE Member_MemberID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Sent_Emails_SentEmailsID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Received_Emails_ReceivedEmailsID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Personal_Labels_PersonalLabelsID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Personal_Contacts_PersonalContactsID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Email_EmailID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Personal_Blockeds_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE Personal_Categories_PersonalCategoriesID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Label_LabelID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Icon_IconID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Settings_SettingsID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Blocked_BlockedID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Contact_ContactID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Personal_Default_Labels_PersonalDefaultLabelsID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Theme_ThemeID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Category_CategoryID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Received_Sent_Emails_To_Labels_ReceivedSentEmailsToLabelsID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Contact_Details_ContactDetailsID_seq CASCADE');
        $this->addSql('DROP SEQUENCE Default_Label_DefaultLabelID_seq CASCADE');
        $this->addSql('DROP TABLE Member');
        $this->addSql('DROP TABLE Personal_Settings');
        $this->addSql('DROP TABLE Personal_Themes');
        $this->addSql('DROP TABLE Sent_Emails');
        $this->addSql('DROP TABLE Received_Emails');
        $this->addSql('DROP TABLE Personal_Labels');
        $this->addSql('DROP TABLE Personal_Contacts');
        $this->addSql('DROP TABLE Email');
        $this->addSql('DROP TABLE Personal_Blockeds');
        $this->addSql('DROP TABLE Personal_Categories');
        $this->addSql('DROP TABLE Label');
        $this->addSql('DROP TABLE Icon');
        $this->addSql('DROP TABLE Settings');
        $this->addSql('DROP TABLE Blocked');
        $this->addSql('DROP TABLE Contact');
        $this->addSql('DROP TABLE Personal_Default_Labels');
        $this->addSql('DROP TABLE Theme');
        $this->addSql('DROP TABLE Category');
        $this->addSql('DROP TABLE Received_Sent_Emails_To_Labels');
        $this->addSql('DROP TABLE Contact_Details');
        $this->addSql('DROP TABLE Default_Label');
    }
}
