<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402123111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note_frais ADD budget_id INT NOT NULL');
        $this->addSql('ALTER TABLE note_frais ADD CONSTRAINT FK_4050EF4F36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id)');
        $this->addSql('CREATE INDEX IDX_4050EF4F36ABA6B8 ON note_frais (budget_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note_frais DROP FOREIGN KEY FK_4050EF4F36ABA6B8');
        $this->addSql('DROP INDEX IDX_4050EF4F36ABA6B8 ON note_frais');
        $this->addSql('ALTER TABLE note_frais DROP budget_id');
    }
}
