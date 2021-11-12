<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211022114538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__travaux AS SELECT id, date_debut, date_fin, adresse_debut, adresse_fin FROM travaux');
        $this->addSql('DROP TABLE travaux');
        $this->addSql('CREATE TABLE travaux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, adresse_debut VARCHAR(255) NOT NULL COLLATE BINARY, adresse_fin VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_6C24F39BB70485B3 FOREIGN KEY (calque_id) REFERENCES calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO travaux (id, date_debut, date_fin, adresse_debut, adresse_fin) SELECT id, date_debut, date_fin, adresse_debut, adresse_fin FROM __temp__travaux');
        $this->addSql('DROP TABLE __temp__travaux');
        $this->addSql('CREATE INDEX IDX_6C24F39BB70485B3 ON travaux (calque_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_6C24F39BB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__travaux AS SELECT id, date_debut, date_fin, adresse_debut, adresse_fin FROM travaux');
        $this->addSql('DROP TABLE travaux');
        $this->addSql('CREATE TABLE travaux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, adresse_debut VARCHAR(255) NOT NULL, adresse_fin VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO travaux (id, date_debut, date_fin, adresse_debut, adresse_fin) SELECT id, date_debut, date_fin, adresse_debut, adresse_fin FROM __temp__travaux');
        $this->addSql('DROP TABLE __temp__travaux');
    }
}
