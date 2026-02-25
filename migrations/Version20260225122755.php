<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225122755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_YES (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE website (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, last_status INT DEFAULT NULL, is_up TINYINT NOT NULL, user_id INT NOT NULL, INDEX IDX_476F5DE7A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE website_check (id INT AUTO_INCREMENT NOT NULL, status INT NOT NULL, is_up TINYINT NOT NULL, checked_at DATETIME NOT NULL, website_id INT NOT NULL, INDEX IDX_6BE41CDF18F45C82 (website_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website_check ADD CONSTRAINT FK_6BE41CDF18F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7A76ED395');
        $this->addSql('ALTER TABLE website_check DROP FOREIGN KEY FK_6BE41CDF18F45C82');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE website');
        $this->addSql('DROP TABLE website_check');
    }
}
