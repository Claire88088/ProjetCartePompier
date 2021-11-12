<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211022114822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_autoroute AS SELECT id, type, adresse, coordonnees_gps FROM element_autoroute');
        $this->addSql('DROP TABLE element_autoroute');
        $this->addSql('CREATE TABLE element_autoroute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, adresse VARCHAR(255) NOT NULL COLLATE BINARY, coordonnees_gps VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_C4C9AE45B70485B3 FOREIGN KEY (calque_id) REFERENCES calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO element_autoroute (id, type, adresse, coordonnees_gps) SELECT id, type, adresse, coordonnees_gps FROM __temp__element_autoroute');
        $this->addSql('DROP TABLE __temp__element_autoroute');
        $this->addSql('CREATE INDEX IDX_C4C9AE45B70485B3 ON element_autoroute (calque_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__etablissement_repertorie AS SELECT id, type, nom, adresse, coordonnees_gps, description, photo, lien FROM etablissement_repertorie');
        $this->addSql('DROP TABLE etablissement_repertorie');
        $this->addSql('CREATE TABLE etablissement_repertorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, nom VARCHAR(255) NOT NULL COLLATE BINARY, adresse VARCHAR(255) NOT NULL COLLATE BINARY, coordonnees_gps VARCHAR(255) NOT NULL COLLATE BINARY, description VARCHAR(255) NOT NULL COLLATE BINARY, photo VARCHAR(255) NOT NULL COLLATE BINARY, lien VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_B170F90CB70485B3 FOREIGN KEY (calque_id) REFERENCES calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO etablissement_repertorie (id, type, nom, adresse, coordonnees_gps, description, photo, lien) SELECT id, type, nom, adresse, coordonnees_gps, description, photo, lien FROM __temp__etablissement_repertorie');
        $this->addSql('DROP TABLE __temp__etablissement_repertorie');
        $this->addSql('CREATE INDEX IDX_B170F90CB70485B3 ON etablissement_repertorie (calque_id)');
        $this->addSql('DROP INDEX IDX_6C24F39BB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__travaux AS SELECT id, calque_id, date_debut, date_fin, adresse_debut, adresse_fin FROM travaux');
        $this->addSql('DROP TABLE travaux');
        $this->addSql('CREATE TABLE travaux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, adresse_debut VARCHAR(255) NOT NULL COLLATE BINARY, adresse_fin VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_6C24F39BB70485B3 FOREIGN KEY (calque_id) REFERENCES calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO travaux (id, calque_id, date_debut, date_fin, adresse_debut, adresse_fin) SELECT id, calque_id, date_debut, date_fin, adresse_debut, adresse_fin FROM __temp__travaux');
        $this->addSql('DROP TABLE __temp__travaux');
        $this->addSql('CREATE INDEX IDX_6C24F39BB70485B3 ON travaux (calque_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_C4C9AE45B70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_autoroute AS SELECT id, type, adresse, coordonnees_gps FROM element_autoroute');
        $this->addSql('DROP TABLE element_autoroute');
        $this->addSql('CREATE TABLE element_autoroute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, coordonnees_gps VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO element_autoroute (id, type, adresse, coordonnees_gps) SELECT id, type, adresse, coordonnees_gps FROM __temp__element_autoroute');
        $this->addSql('DROP TABLE __temp__element_autoroute');
        $this->addSql('DROP INDEX IDX_B170F90CB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__etablissement_repertorie AS SELECT id, type, nom, adresse, coordonnees_gps, description, photo, lien FROM etablissement_repertorie');
        $this->addSql('DROP TABLE etablissement_repertorie');
        $this->addSql('CREATE TABLE etablissement_repertorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, coordonnees_gps VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO etablissement_repertorie (id, type, nom, adresse, coordonnees_gps, description, photo, lien) SELECT id, type, nom, adresse, coordonnees_gps, description, photo, lien FROM __temp__etablissement_repertorie');
        $this->addSql('DROP TABLE __temp__etablissement_repertorie');
        $this->addSql('DROP INDEX IDX_6C24F39BB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__travaux AS SELECT id, calque_id, date_debut, date_fin, adresse_debut, adresse_fin FROM travaux');
        $this->addSql('DROP TABLE travaux');
        $this->addSql('CREATE TABLE travaux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, adresse_debut VARCHAR(255) NOT NULL, adresse_fin VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO travaux (id, calque_id, date_debut, date_fin, adresse_debut, adresse_fin) SELECT id, calque_id, date_debut, date_fin, adresse_debut, adresse_fin FROM __temp__travaux');
        $this->addSql('DROP TABLE __temp__travaux');
        $this->addSql('CREATE INDEX IDX_6C24F39BB70485B3 ON travaux (calque_id)');
    }
}
