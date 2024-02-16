<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240210220536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE moyen_transport (id INT AUTO_INCREMENT NOT NULL, categorie_moyen VARCHAR(255) NOT NULL, type_moyen VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE voyage (id INT AUTO_INCREMENT NOT NULL, depart VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, date_dep DATETIME NOT NULL, date_arr DATETIME NOT NULL, heure_dep DATETIME NOT NULL, heure_arr DATETIME NOT NULL, prix DOUBLE PRECISION NOT NULL, nombre_place_dispo INT NOT NULL, moyen_transport_id INT DEFAULT NULL, hebergement_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_3F9D89553ED8D53F (moyen_transport_id), INDEX IDX_3F9D895523BB0F66 (hebergement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D89553ED8D53F FOREIGN KEY (moyen_transport_id) REFERENCES moyen_transport (id)');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D895523BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D89553ED8D53F');
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D895523BB0F66');
        $this->addSql('DROP TABLE moyen_transport');
        $this->addSql('DROP TABLE voyage');
    }
}
