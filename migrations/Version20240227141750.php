<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227141750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_hebergement ADD hebergement_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C023BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
        $this->addSql('CREATE INDEX IDX_843E00C023BB0F66 ON reservation_hebergement (hebergement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C023BB0F66');
        $this->addSql('DROP INDEX IDX_843E00C023BB0F66 ON reservation_hebergement');
        $this->addSql('ALTER TABLE reservation_hebergement DROP hebergement_id');
    }
}
