<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302185456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE hebergement ADD image VARCHAR(255) NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD number_likes INT DEFAULT NULL, ADD number_dislikes INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C023BB0F66');
        $this->addSql('DROP INDEX IDX_843E00C023BB0F66 ON reservation_hebergement');
        $this->addSql('ALTER TABLE reservation_hebergement DROP hebergement_id');
        $this->addSql('ALTER TABLE reservation_voyage DROP FOREIGN KEY FK_776CC0CE68C9E5AF');
        $this->addSql('DROP INDEX IDX_776CC0CE68C9E5AF ON reservation_voyage');
        $this->addSql('ALTER TABLE reservation_voyage DROP voyage_id');
        $this->addSql('ALTER TABLE type_hebergement ADD color VARCHAR(7) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP email');
        $this->addSql('ALTER TABLE hebergement DROP image, DROP name, DROP number_likes, DROP number_dislikes');
        $this->addSql('ALTER TABLE reservation_hebergement ADD hebergement_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C023BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
        $this->addSql('CREATE INDEX IDX_843E00C023BB0F66 ON reservation_hebergement (hebergement_id)');
        $this->addSql('ALTER TABLE reservation_voyage ADD voyage_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation_voyage ADD CONSTRAINT FK_776CC0CE68C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id)');
        $this->addSql('CREATE INDEX IDX_776CC0CE68C9E5AF ON reservation_voyage (voyage_id)');
        $this->addSql('ALTER TABLE type_hebergement DROP color');
    }
}
