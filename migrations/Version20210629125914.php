<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629125914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE forums');
        $this->addSql('DROP TABLE forums_response');
        $this->addSql('ALTER TABLE cms_news ADD category_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) NOT NULL, CHANGE date date DATETIME NOT NULL, CHANGE author author VARCHAR(255) NOT NULL, CHANGE img_url img_url VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cms_news ADD CONSTRAINT FK_DA45342D12469DE2 FOREIGN KEY (category_id) REFERENCES cms_news_category (id)');
        $this->addSql('CREATE INDEX IDX_DA45342D12469DE2 ON cms_news (category_id)');
        $this->addSql('ALTER TABLE cms_news_category CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE cms_users DROP discord');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forums (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, description VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, parent INT DEFAULT NULL, is_visible TINYINT(1) NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE forums_response (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATE NOT NULL, modify_at DATE DEFAULT NULL, content TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, edit_reason VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_bin`, forum INT NOT NULL, type LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, name VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_bin`, INDEX IDX_7B932993A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE forums_response ADD CONSTRAINT FK_7B932993A76ED395 FOREIGN KEY (user_id) REFERENCES cms_users (webId)');
        $this->addSql('ALTER TABLE cms_news DROP FOREIGN KEY FK_DA45342D12469DE2');
        $this->addSql('DROP INDEX IDX_DA45342D12469DE2 ON cms_news');
        $this->addSql('ALTER TABLE cms_news DROP category_id, CHANGE title title TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE date date DATE NOT NULL, CHANGE author author TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE img_url img_url TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE slug slug TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE cms_news_category MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE cms_news_category DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE cms_news_category CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE cms_users ADD discord VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`');
    }
}
