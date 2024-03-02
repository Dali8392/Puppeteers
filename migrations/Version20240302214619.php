<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302214619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moyen_transport ADD id_modele VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F42537D8363530B5 ON moyen_transport (id_modele)');
        $this->addSql('ALTER TABLE voyage DROP INDEX UNIQ_3F9D89553ED8D53F, ADD INDEX IDX_3F9D89553ED8D53F (moyen_transport_id)');
        $this->addSql('ALTER TABLE voyage ADD description VARCHAR(255) DEFAULT NULL, CHANGE date_dep date_dep DATE NOT NULL, CHANGE date_arr date_arr DATE NOT NULL, CHANGE heure_dep heure_dep TIME NOT NULL, CHANGE heure_arr heure_arr TIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_F42537D8363530B5 ON moyen_transport');
        $this->addSql('ALTER TABLE moyen_transport DROP id_modele');
        $this->addSql('ALTER TABLE voyage DROP INDEX IDX_3F9D89553ED8D53F, ADD UNIQUE INDEX UNIQ_3F9D89553ED8D53F (moyen_transport_id)');
        $this->addSql('ALTER TABLE voyage DROP description, CHANGE date_dep date_dep DATETIME NOT NULL, CHANGE date_arr date_arr DATETIME NOT NULL, CHANGE heure_dep heure_dep DATETIME NOT NULL, CHANGE heure_arr heure_arr DATETIME NOT NULL');
    }
}
