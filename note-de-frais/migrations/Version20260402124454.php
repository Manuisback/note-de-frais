<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402124454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE justificatif (id INT AUTO_INCREMENT NOT NULL, nom_fichier VARCHAR(255) NOT NULL, chemin_fichier VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, note_frais_id INT NOT NULL, INDEX IDX_90D3C5DCAF0AB15A (note_frais_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE justificatif ADD CONSTRAINT FK_90D3C5DCAF0AB15A FOREIGN KEY (note_frais_id) REFERENCES note_frais (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE justificatif DROP FOREIGN KEY FK_90D3C5DCAF0AB15A');
        $this->addSql('DROP TABLE justificatif');
    }
}
