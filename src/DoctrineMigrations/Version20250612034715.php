<?php

declare(strict_types=1);

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612034715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE VRR_ReadingRoomApplications (id INT AUTO_INCREMENT NOT NULL, application_id INT DEFAULT NULL, settings_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_D30EF1B53E030ACD (application_id), INDEX IDX_D30EF1B559949888 (settings_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE VRR_ReadingRoomSettings (id INT AUTO_INCREMENT NOT NULL, book_suggestions_association_type_id INT DEFAULT NULL, settings_key VARCHAR(32) NOT NULL, show_price TINYINT(1) DEFAULT 0 NOT NULL, open_file TINYINT(1) DEFAULT 0 NOT NULL COMMENT 'Setting for ''ng2-pdfjs-viewer'' Component', view_bookmark TINYINT(1) DEFAULT 0 NOT NULL COMMENT 'Setting for ''ng2-pdfjs-viewer'' Component', download TINYINT(1) DEFAULT 0 NOT NULL COMMENT 'Setting for ''ng2-pdfjs-viewer'' Component', print TINYINT(1) DEFAULT 0 NOT NULL COMMENT 'Setting for ''ng2-pdfjs-viewer'' Component', INDEX IDX_F390BFA52D6889DA (book_suggestions_association_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE VSCAT_BookAuthors (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_39FF984B989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE VSCAT_Authors_Genres (author_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_4E0E12FFF675F31B (author_id), INDEX IDX_4E0E12FF4296D31F (genre_id), PRIMARY KEY(author_id, genre_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE VSCAT_BookAuthors_Photos (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, path VARCHAR(255) NOT NULL, original_name VARCHAR(255) DEFAULT '' NOT NULL COMMENT 'The Original Name of the File.', INDEX IDX_697C7667E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE VSCAT_BookGenres (id INT AUTO_INCREMENT NOT NULL, taxon_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E605C468DE13F470 (taxon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE VSCAT_Books_Genres (book_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_8846F95916A2B381 (book_id), INDEX IDX_8846F9594296D31F (genre_id), PRIMARY KEY(book_id, genre_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE VSCAT_Books_Authors (book_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_684FDB4C16A2B381 (book_id), INDEX IDX_684FDB4CF675F31B (author_id), PRIMARY KEY(book_id, author_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VRR_ReadingRoomApplications ADD CONSTRAINT FK_D30EF1B53E030ACD FOREIGN KEY (application_id) REFERENCES VSAPP_Applications (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VRR_ReadingRoomApplications ADD CONSTRAINT FK_D30EF1B559949888 FOREIGN KEY (settings_id) REFERENCES VRR_ReadingRoomSettings (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VRR_ReadingRoomSettings ADD CONSTRAINT FK_F390BFA52D6889DA FOREIGN KEY (book_suggestions_association_type_id) REFERENCES VSCAT_AssociationTypes (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Authors_Genres ADD CONSTRAINT FK_4E0E12FFF675F31B FOREIGN KEY (author_id) REFERENCES VSCAT_BookAuthors (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Authors_Genres ADD CONSTRAINT FK_4E0E12FF4296D31F FOREIGN KEY (genre_id) REFERENCES VSCAT_BookGenres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_BookAuthors_Photos ADD CONSTRAINT FK_697C7667E3C61F9 FOREIGN KEY (owner_id) REFERENCES VSCAT_BookAuthors (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_BookGenres ADD CONSTRAINT FK_E605C468DE13F470 FOREIGN KEY (taxon_id) REFERENCES VSAPP_Taxons (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Books_Genres ADD CONSTRAINT FK_8846F95916A2B381 FOREIGN KEY (book_id) REFERENCES VSCAT_Products (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Books_Genres ADD CONSTRAINT FK_8846F9594296D31F FOREIGN KEY (genre_id) REFERENCES VSCAT_BookGenres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Books_Authors ADD CONSTRAINT FK_684FDB4C16A2B381 FOREIGN KEY (book_id) REFERENCES VSCAT_Products (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Books_Authors ADD CONSTRAINT FK_684FDB4CF675F31B FOREIGN KEY (author_id) REFERENCES VSCAT_BookAuthors (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSAPP_Settings DROP FOREIGN KEY FK_4A491FD507FAB6A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSAPP_Settings CHANGE maintenance_page_id maintenance_page_id  INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSAPP_Settings ADD CONSTRAINT FK_4A491FD507FAB6A FOREIGN KEY (maintenance_page_id ) REFERENCES VSCMS_Pages (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings (maintenance_page_id )
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_PricingPlanCategories ADD CONSTRAINT FK_10C2B955727ACA70 FOREIGN KEY (parent_id) REFERENCES VSCAT_PricingPlanCategories (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_PricingPlanCategories ADD CONSTRAINT FK_10C2B955DE13F470 FOREIGN KEY (taxon_id) REFERENCES VSAPP_Taxons (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_PricingPlanSubscriptions ADD CONSTRAINT FK_EA3E01A0A76ED395 FOREIGN KEY (user_id) REFERENCES VSUM_Users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_ProductCategories ADD CONSTRAINT FK_7ADE9A79DE13F470 FOREIGN KEY (taxon_id) REFERENCES VSAPP_Taxons (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_ProductFiles ADD locale VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Products ADD user_id INT DEFAULT NULL, ADD document_id INT DEFAULT NULL, ADD book_type ENUM('pdf', 'vankosoft_document')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Products ADD CONSTRAINT FK_D8F34E8CA76ED395 FOREIGN KEY (user_id) REFERENCES VSUM_Users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Products ADD CONSTRAINT FK_D8F34E8CC33F7837 FOREIGN KEY (document_id) REFERENCES VSCMS_Documents (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D8F34E8CA76ED395 ON VSCAT_Products (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_D8F34E8CC33F7837 ON VSCAT_Products (document_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSPAY_CustomerGroups ADD CONSTRAINT FK_8D3A9BC4DE13F470 FOREIGN KEY (taxon_id) REFERENCES VSAPP_Taxons (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSPAY_Order ADD CONSTRAINT FK_87954502A76ED395 FOREIGN KEY (user_id) REFERENCES VSUM_Users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSPAY_Promotion_Applications ADD CONSTRAINT FK_1D3F36D53E030ACD FOREIGN KEY (application_id) REFERENCES VSAPP_Applications (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSUM_UsersInfo CHANGE title title ENUM('mr', 'mrs', 'miss')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSUS_NewsletterSubscriptions ADD CONSTRAINT FK_E521F0DCA76ED395 FOREIGN KEY (user_id) REFERENCES VSUM_Users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE VRR_ReadingRoomApplications DROP FOREIGN KEY FK_D30EF1B53E030ACD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VRR_ReadingRoomApplications DROP FOREIGN KEY FK_D30EF1B559949888
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VRR_ReadingRoomSettings DROP FOREIGN KEY FK_F390BFA52D6889DA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Authors_Genres DROP FOREIGN KEY FK_4E0E12FFF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Authors_Genres DROP FOREIGN KEY FK_4E0E12FF4296D31F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_BookAuthors_Photos DROP FOREIGN KEY FK_697C7667E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_BookGenres DROP FOREIGN KEY FK_E605C468DE13F470
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Books_Genres DROP FOREIGN KEY FK_8846F95916A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Books_Genres DROP FOREIGN KEY FK_8846F9594296D31F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Books_Authors DROP FOREIGN KEY FK_684FDB4C16A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Books_Authors DROP FOREIGN KEY FK_684FDB4CF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE VRR_ReadingRoomApplications
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE VRR_ReadingRoomSettings
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE VSCAT_BookAuthors
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE VSCAT_Authors_Genres
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE VSCAT_BookAuthors_Photos
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE VSCAT_BookGenres
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE VSCAT_Books_Genres
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE VSCAT_Books_Authors
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSAPP_Settings DROP FOREIGN KEY FK_4A491FD507FAB6A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSAPP_Settings CHANGE maintenance_page_id  maintenance_page_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSAPP_Settings ADD CONSTRAINT FK_4A491FD507FAB6A FOREIGN KEY (maintenance_page_id) REFERENCES VSCMS_Pages (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings (maintenance_page_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_PricingPlanCategories DROP FOREIGN KEY FK_10C2B955727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_PricingPlanCategories DROP FOREIGN KEY FK_10C2B955DE13F470
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_PricingPlanSubscriptions DROP FOREIGN KEY FK_EA3E01A0A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_ProductCategories DROP FOREIGN KEY FK_7ADE9A79DE13F470
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_ProductFiles DROP locale
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Products DROP FOREIGN KEY FK_D8F34E8CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Products DROP FOREIGN KEY FK_D8F34E8CC33F7837
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D8F34E8CA76ED395 ON VSCAT_Products
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_D8F34E8CC33F7837 ON VSCAT_Products
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSCAT_Products DROP user_id, DROP document_id, DROP book_type
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSPAY_CustomerGroups DROP FOREIGN KEY FK_8D3A9BC4DE13F470
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSPAY_Order DROP FOREIGN KEY FK_87954502A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSPAY_Promotion_Applications DROP FOREIGN KEY FK_1D3F36D53E030ACD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSUM_UsersInfo CHANGE title title VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE VSUS_NewsletterSubscriptions DROP FOREIGN KEY FK_E521F0DCA76ED395
        SQL);
    }
}
