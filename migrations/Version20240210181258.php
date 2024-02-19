<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240210181258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, ville VARCHAR(20) NOT NULL, prix VARCHAR(255) NOT NULL, details VARCHAR(255) NOT NULL, heure VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE guide (id VARCHAR(10) NOT NULL, name VARCHAR(20) NOT NULL, last_name VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, cin VARCHAR(8) NOT NULL, langue VARCHAR(20) NOT NULL, ville VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event ADD user_creator VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE hebergement ADD capacite INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE guide');
        $this->addSql('ALTER TABLE event DROP user_creator');
        $this->addSql('ALTER TABLE hebergement DROP capacite');
    }
}
