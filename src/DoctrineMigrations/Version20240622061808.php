<?php

declare(strict_types=1);

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240622061808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE VRR_ReadingRoomApplications (id INT AUTO_INCREMENT NOT NULL, application_id INT DEFAULT NULL, settings_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_D30EF1B53E030ACD (application_id), INDEX IDX_D30EF1B559949888 (settings_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE VRR_ReadingRoomApplications ADD CONSTRAINT FK_D30EF1B53E030ACD FOREIGN KEY (application_id) REFERENCES VSAPP_Applications (id)');
        $this->addSql('ALTER TABLE VRR_ReadingRoomApplications ADD CONSTRAINT FK_D30EF1B559949888 FOREIGN KEY (settings_id) REFERENCES VRR_ReadingRoomSettings (id)');
        $this->addSql('ALTER TABLE VSAPP_Settings DROP FOREIGN KEY FK_4A491FD507FAB6A');
        $this->addSql('DROP INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings');
        $this->addSql('ALTER TABLE VSAPP_Settings CHANGE maintenance_page_id maintenance_page_id  INT DEFAULT NULL');
        $this->addSql('ALTER TABLE VSAPP_Settings ADD CONSTRAINT FK_4A491FD507FAB6A FOREIGN KEY (maintenance_page_id ) REFERENCES VSCMS_Pages (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings (maintenance_page_id )');
        $this->addSql('ALTER TABLE VSCAT_PricingPlanSubscriptions CHANGE gateway_attributes gateway_attributes JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE VSCAT_PricingPlans CHANGE gateway_attributes gateway_attributes JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE VSCAT_Products DROP average_rating');
        $this->addSql('ALTER TABLE VSPAY_Adjustments CHANGE details details JSON NOT NULL');
        $this->addSql('ALTER TABLE VSPAY_GatewayConfig CHANGE config config JSON NOT NULL, CHANGE sandbox_config sandbox_config JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE VSPAY_Payment CHANGE details details JSON NOT NULL');
        $this->addSql('ALTER TABLE VSUM_Users CHANGE payment_details payment_details JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE VSUM_UsersInfo CHANGE title title ENUM(\'mr\', \'mrs\', \'miss\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE VRR_ReadingRoomApplications DROP FOREIGN KEY FK_D30EF1B53E030ACD');
        $this->addSql('ALTER TABLE VRR_ReadingRoomApplications DROP FOREIGN KEY FK_D30EF1B559949888');
        $this->addSql('DROP TABLE VRR_ReadingRoomApplications');
        $this->addSql('ALTER TABLE VSAPP_Settings DROP FOREIGN KEY FK_4A491FD507FAB6A');
        $this->addSql('DROP INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings');
        $this->addSql('ALTER TABLE VSAPP_Settings CHANGE maintenance_page_id  maintenance_page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE VSAPP_Settings ADD CONSTRAINT FK_4A491FD507FAB6A FOREIGN KEY (maintenance_page_id) REFERENCES VSCMS_Pages (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings (maintenance_page_id)');
        $this->addSql('ALTER TABLE VSCAT_PricingPlanSubscriptions CHANGE gateway_attributes gateway_attributes JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE VSCAT_PricingPlans CHANGE gateway_attributes gateway_attributes JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE VSCAT_Products ADD average_rating DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE VSPAY_Adjustments CHANGE details details JSON NOT NULL');
        $this->addSql('ALTER TABLE VSPAY_GatewayConfig CHANGE config config JSON NOT NULL, CHANGE sandbox_config sandbox_config JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE VSPAY_Payment CHANGE details details JSON NOT NULL');
        $this->addSql('ALTER TABLE VSUM_Users CHANGE payment_details payment_details JSON NOT NULL');
        $this->addSql('ALTER TABLE VSUM_UsersInfo CHANGE title title VARCHAR(255) DEFAULT NULL');
    }
}
