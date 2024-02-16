<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212141755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, ville VARCHAR(20) NOT NULL, prix VARCHAR(255) NOT NULL, details VARCHAR(255) NOT NULL, heure VARCHAR(25) NOT NULL, etat INT NOT NULL, guide_id VARCHAR(10) DEFAULT NULL, INDEX IDX_B8755515D7ED1D4B (guide_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE activite_user (activite_id INT NOT NULL, user_id VARCHAR(10) NOT NULL, INDEX IDX_FA43CF3B9B0F88B1 (activite_id), INDEX IDX_FA43CF3BA76ED395 (user_id), PRIMARY KEY(activite_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, commentaire VARCHAR(255) NOT NULL, hebergement_id INT DEFAULT NULL, INDEX IDX_8F91ABF023BB0F66 (hebergement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, date VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, sender VARCHAR(10) NOT NULL, event_id INT DEFAULT NULL, INDEX IDX_9474526C71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, type VARCHAR(10) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, event_location VARCHAR(255) NOT NULL, duree DATETIME NOT NULL, max_participants INT NOT NULL, budget_allocated DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, user_creator VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id VARCHAR(10) NOT NULL, INDEX IDX_92589AE271F7E88B (event_id), INDEX IDX_92589AE2A76ED395 (user_id), PRIMARY KEY(event_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE guide (id VARCHAR(10) NOT NULL, name VARCHAR(20) NOT NULL, last_name VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, cin VARCHAR(8) NOT NULL, role VARCHAR(10) NOT NULL, langue VARCHAR(20) NOT NULL, ville VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE hebergement (id INT AUTO_INCREMENT NOT NULL, adresse VARCHAR(255) NOT NULL, tarif DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, date_disponibilte DATETIME NOT NULL, capacite INT NOT NULL, type_hebergement_id INT DEFAULT NULL, user_id VARCHAR(10) DEFAULT NULL, INDEX IDX_4852DD9C757826F2 (type_hebergement_id), INDEX IDX_4852DD9CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE moyen_transport (id INT AUTO_INCREMENT NOT NULL, categorie_moyen VARCHAR(255) NOT NULL, type_moyen VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, methode VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation_hebergement (id INT AUTO_INCREMENT NOT NULL, id_user VARCHAR(10) DEFAULT NULL, date DATETIME NOT NULL, duree DATETIME NOT NULL, max INT NOT NULL, paiement_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_843E00C02A4C4478 (paiement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation_voyage (id INT AUTO_INCREMENT NOT NULL, max INT NOT NULL, id_user VARCHAR(10) NOT NULL, paiement_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_776CC0CE2A4C4478 (paiement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE type_hebergement (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id VARCHAR(10) NOT NULL, name VARCHAR(20) NOT NULL, last_name VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, cin VARCHAR(8) NOT NULL, role VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE voyage (id INT AUTO_INCREMENT NOT NULL, depart VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, date_dep DATETIME NOT NULL, date_arr DATETIME NOT NULL, heure_dep DATETIME NOT NULL, heure_arr DATETIME NOT NULL, prix DOUBLE PRECISION NOT NULL, nombre_place_dispo INT NOT NULL, moyen_transport_id INT DEFAULT NULL, hebergement_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_3F9D89553ED8D53F (moyen_transport_id), INDEX IDX_3F9D895523BB0F66 (hebergement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515D7ED1D4B FOREIGN KEY (guide_id) REFERENCES guide (id)');
        $this->addSql('ALTER TABLE activite_user ADD CONSTRAINT FK_FA43CF3B9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activite_user ADD CONSTRAINT FK_FA43CF3BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF023BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9C757826F2 FOREIGN KEY (type_hebergement_id) REFERENCES type_hebergement (id)');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C02A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id)');
        $this->addSql('ALTER TABLE reservation_voyage ADD CONSTRAINT FK_776CC0CE2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id)');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D89553ED8D53F FOREIGN KEY (moyen_transport_id) REFERENCES moyen_transport (id)');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D895523BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515D7ED1D4B');
        $this->addSql('ALTER TABLE activite_user DROP FOREIGN KEY FK_FA43CF3B9B0F88B1');
        $this->addSql('ALTER TABLE activite_user DROP FOREIGN KEY FK_FA43CF3BA76ED395');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF023BB0F66');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C71F7E88B');
        $this->addSql('ALTER TABLE event_user DROP FOREIGN KEY FK_92589AE271F7E88B');
        $this->addSql('ALTER TABLE event_user DROP FOREIGN KEY FK_92589AE2A76ED395');
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9C757826F2');
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9CA76ED395');
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C02A4C4478');
        $this->addSql('ALTER TABLE reservation_voyage DROP FOREIGN KEY FK_776CC0CE2A4C4478');
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D89553ED8D53F');
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D895523BB0F66');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE activite_user');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('DROP TABLE guide');
        $this->addSql('DROP TABLE hebergement');
        $this->addSql('DROP TABLE moyen_transport');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE reservation_hebergement');
        $this->addSql('DROP TABLE reservation_voyage');
        $this->addSql('DROP TABLE type_hebergement');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE voyage');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
