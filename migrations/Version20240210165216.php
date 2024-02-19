<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240210165216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, commentaire VARCHAR(255) NOT NULL, hebergement_id INT DEFAULT NULL, INDEX IDX_8F91ABF023BB0F66 (hebergement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE type_hebergement (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF023BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
        $this->addSql('ALTER TABLE hebergement ADD type_hebergement_id INT DEFAULT NULL, ADD user_id VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9C757826F2 FOREIGN KEY (type_hebergement_id) REFERENCES type_hebergement (id)');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4852DD9C757826F2 ON hebergement (type_hebergement_id)');
        $this->addSql('CREATE INDEX IDX_4852DD9CA76ED395 ON hebergement (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF023BB0F66');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE type_hebergement');
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9C757826F2');
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9CA76ED395');
        $this->addSql('DROP INDEX IDX_4852DD9C757826F2 ON hebergement');
        $this->addSql('DROP INDEX IDX_4852DD9CA76ED395 ON hebergement');
        $this->addSql('ALTER TABLE hebergement DROP type_hebergement_id, DROP user_id');
    }
}
