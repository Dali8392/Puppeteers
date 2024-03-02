<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302204412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C023BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
        $this->addSql('CREATE INDEX IDX_843E00C023BB0F66 ON reservation_hebergement (hebergement_id)');
        $this->addSql('ALTER TABLE reservation_voyage ADD date DATETIME NOT NULL, ADD voyage_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation_voyage ADD CONSTRAINT FK_776CC0CE68C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id)');
        $this->addSql('CREATE INDEX IDX_776CC0CE68C9E5AF ON reservation_voyage (voyage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C023BB0F66');
        $this->addSql('DROP INDEX IDX_843E00C023BB0F66 ON reservation_hebergement');
        $this->addSql('ALTER TABLE reservation_voyage DROP FOREIGN KEY FK_776CC0CE68C9E5AF');
        $this->addSql('DROP INDEX IDX_776CC0CE68C9E5AF ON reservation_voyage');
        $this->addSql('ALTER TABLE reservation_voyage DROP date, DROP voyage_id');
    }
}
