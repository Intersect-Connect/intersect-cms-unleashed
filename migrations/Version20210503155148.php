<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503155148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forums_response DROP FOREIGN KEY user');
        $this->addSql('ALTER TABLE forums_response CHANGE user_id user_id LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE forums_response ADD CONSTRAINT FK_7B932993A76ED395 FOREIGN KEY (user_id) REFERENCES cms_users (id)');
        $this->addSql('ALTER TABLE forums_response RENAME INDEX user TO IDX_7B932993A76ED395');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forums_response DROP FOREIGN KEY FK_7B932993A76ED395');
        $this->addSql('ALTER TABLE forums_response CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forums_response ADD CONSTRAINT user FOREIGN KEY (user_id) REFERENCES cms_users (webId)');
        $this->addSql('ALTER TABLE forums_response RENAME INDEX idx_7b932993a76ed395 TO user');
    }
}
