<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240210190536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, methode VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation_hebergement (id INT AUTO_INCREMENT NOT NULL, id_user VARCHAR(10) DEFAULT NULL, date DATETIME NOT NULL, duree DATETIME NOT NULL, max INT NOT NULL, paiement_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_843E00C02A4C4478 (paiement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation_voyage (id INT AUTO_INCREMENT NOT NULL, max INT NOT NULL, id_user VARCHAR(10) NOT NULL, paiement_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_776CC0CE2A4C4478 (paiement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C02A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id)');
        $this->addSql('ALTER TABLE reservation_voyage ADD CONSTRAINT FK_776CC0CE2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C02A4C4478');
        $this->addSql('ALTER TABLE reservation_voyage DROP FOREIGN KEY FK_776CC0CE2A4C4478');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE reservation_hebergement');
        $this->addSql('DROP TABLE reservation_voyage');
    }
}
