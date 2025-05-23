<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200425170331 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(180) NOT NULL, ADD last_name VARCHAR(180) NOT NULL, ADD address VARCHAR(180) NOT NULL, ADD phone VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A9D1C132 ON user (first_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C808BA5A ON user (last_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D4E6F81 ON user (address)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649444F97DD ON user (phone)');
        $this->addSql('ALTER TABLE generador ADD first_name VARCHAR(180) NOT NULL, ADD last_name VARCHAR(180) NOT NULL, ADD address VARCHAR(180) NOT NULL, ADD phone VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D688D800A9D1C132 ON generador (first_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D688D800C808BA5A ON generador (last_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D688D800D4E6F81 ON generador (address)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D688D800444F97DD ON generador (phone)');
        $this->addSql('ALTER TABLE recolector ADD first_name VARCHAR(180) NOT NULL, ADD last_name VARCHAR(180) NOT NULL, ADD address VARCHAR(180) NOT NULL, ADD phone VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CB9AA69A9D1C132 ON recolector (first_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CB9AA69C808BA5A ON recolector (last_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CB9AA69D4E6F81 ON recolector (address)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CB9AA69444F97DD ON recolector (phone)');
        $this->addSql('ALTER TABLE easy_core_asset_image CHANGE image_created_by image_created_by VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_core_post CHANGE description description VARCHAR(256) DEFAULT NULL, CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE image_filename image_filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_core_category CHANGE description description VARCHAR(256) DEFAULT NULL, CHANGE modified_at modified_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_core_contact CHANGE contact_phone contact_phone VARCHAR(32) DEFAULT NULL, CHANGE contact_is_read contact_is_read TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE easy_core_asset_image CHANGE image_created_by image_created_by VARCHAR(128) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE easy_core_category CHANGE description description VARCHAR(256) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE easy_core_contact CHANGE contact_phone contact_phone VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE contact_is_read contact_is_read TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE easy_core_post CHANGE description description VARCHAR(256) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE image_filename image_filename VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_D688D800A9D1C132 ON generador');
        $this->addSql('DROP INDEX UNIQ_D688D800C808BA5A ON generador');
        $this->addSql('DROP INDEX UNIQ_D688D800D4E6F81 ON generador');
        $this->addSql('DROP INDEX UNIQ_D688D800444F97DD ON generador');
        $this->addSql('ALTER TABLE generador DROP first_name, DROP last_name, DROP address, DROP phone, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('DROP INDEX UNIQ_2CB9AA69A9D1C132 ON recolector');
        $this->addSql('DROP INDEX UNIQ_2CB9AA69C808BA5A ON recolector');
        $this->addSql('DROP INDEX UNIQ_2CB9AA69D4E6F81 ON recolector');
        $this->addSql('DROP INDEX UNIQ_2CB9AA69444F97DD ON recolector');
        $this->addSql('ALTER TABLE recolector DROP first_name, DROP last_name, DROP address, DROP phone, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('DROP INDEX UNIQ_8D93D649A9D1C132 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649C808BA5A ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649D4E6F81 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649444F97DD ON user');
        $this->addSql('ALTER TABLE user DROP first_name, DROP last_name, DROP address, DROP phone, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
