<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413092356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Medias (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_lang (string_id INT AUTO_INCREMENT NOT NULL, str_key VARCHAR(255) NOT NULL, text TEXT NOT NULL, language VARCHAR(2) NOT NULL, PRIMARY KEY(string_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cms_news CHANGE title title VARCHAR(255) NOT NULL, CHANGE date date DATETIME NOT NULL, CHANGE author author VARCHAR(255) NOT NULL, CHANGE img_url img_url VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cms_news RENAME INDEX fk_da45342d12469de2 TO IDX_DA45342D12469DE2');
        $this->addSql('ALTER TABLE cms_users DROP discord');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Medias');
        $this->addSql('DROP TABLE cms_lang');
        $this->addSql('ALTER TABLE cms_news CHANGE title title TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE date date DATE NOT NULL, CHANGE author author TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE img_url img_url TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE slug slug TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE cms_news RENAME INDEX idx_da45342d12469de2 TO FK_DA45342D12469DE2');
        $this->addSql('ALTER TABLE cms_users ADD discord VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`');
    }
}
