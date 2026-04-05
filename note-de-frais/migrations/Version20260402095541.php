<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402095541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ligne_frais (id INT AUTO_INCREMENT NOT NULL, date_depense DATE DEFAULT NULL, objet_despense VARCHAR(255) DEFAULT NULL, kilometres NUMERIC(10, 2) DEFAULT NULL, type_bareme VARCHAR(255) DEFAULT NULL, taux_km NUMERIC(10, 3) DEFAULT NULL, montant_km NUMERIC(10, 2) DEFAULT NULL, montant_transport NUMERIC(10, 2) DEFAULT NULL, montant_autre NUMERIC(10, 2) DEFAULT NULL, total_ligne NUMERIC(10, 2) DEFAULT NULL, note_frais_id INT NOT NULL, INDEX IDX_26ECA6A8AF0AB15A (note_frais_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE ligne_frais ADD CONSTRAINT FK_26ECA6A8AF0AB15A FOREIGN KEY (note_frais_id) REFERENCES note_frais (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_frais DROP FOREIGN KEY FK_26ECA6A8AF0AB15A');
        $this->addSql('DROP TABLE ligne_frais');
    }
}
