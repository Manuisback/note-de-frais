<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402091314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note_frais (id INT AUTO_INCREMENT NOT NULL, date_demande DATE NOT NULL, raison_depense LONGTEXT NOT NULL, total_km NUMERIC(10, 2) NOT NULL, montant_km NUMERIC(10, 2) NOT NULL, total_transport NUMERIC(10, 2) NOT NULL, total_autre NUMERIC(10, 2) NOT NULL, total_general NUMERIC(10, 2) NOT NULL, montant_abandon NUMERIC(10, 2) NOT NULL, montant_rembourse NUMERIC(10, 2) NOT NULL, iban VARCHAR(34) DEFAULT NULL, bic VARCHAR(20) DEFAULT NULL, statut VARCHAR(30) NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE note_frais');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
