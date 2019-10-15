<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191015123716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE prueba');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE prueba (id INT AUTO_INCREMENT NOT NULL, main_image INT DEFAULT NULL, title VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(256) DEFAULT NULL COLLATE utf8mb4_unicode_ci, content LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, publish_since LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, publish_until LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, publish_status VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, INDEX IDX_46711E436661B719 (main_image), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE prueba ADD CONSTRAINT FK_46711E436661B719 FOREIGN KEY (main_image) REFERENCES easy_core_asset_image (image_id)');
        $this->addSql('DROP TABLE user');
    }
}
