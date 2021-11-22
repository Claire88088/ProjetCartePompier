<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211119143227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_etablissment_repertorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('DROP INDEX IDX_C4C9AE45B70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_autoroute AS SELECT id, calque_id, type, adresse, latitude, longitude FROM element_autoroute');
        $this->addSql('DROP TABLE element_autoroute');
        $this->addSql('CREATE TABLE element_autoroute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, adresse VARCHAR(255) NOT NULL COLLATE BINARY, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL, CONSTRAINT FK_C4C9AE45B70485B3 FOREIGN KEY (calque_id) REFERENCES calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO element_autoroute (id, calque_id, type, adresse, latitude, longitude) SELECT id, calque_id, type, adresse, latitude, longitude FROM __temp__element_autoroute');
        $this->addSql('DROP TABLE __temp__element_autoroute');
        $this->addSql('CREATE INDEX IDX_C4C9AE45B70485B3 ON element_autoroute (calque_id)');
        $this->addSql('DROP INDEX IDX_477ED079FA20172D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_er AS SELECT id, etablissement_repertorie_id, type, adresse, latitude, longitude FROM element_er');
        $this->addSql('DROP TABLE element_er');
        $this->addSql('CREATE TABLE element_er (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, etablissement_repertorie_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, adresse VARCHAR(255) NOT NULL COLLATE BINARY, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL, CONSTRAINT FK_477ED079FA20172D FOREIGN KEY (etablissement_repertorie_id) REFERENCES etablissement_repertorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO element_er (id, etablissement_repertorie_id, type, adresse, latitude, longitude) SELECT id, etablissement_repertorie_id, type, adresse, latitude, longitude FROM __temp__element_er');
        $this->addSql('DROP TABLE __temp__element_er');
        $this->addSql('CREATE INDEX IDX_477ED079FA20172D ON element_er (etablissement_repertorie_id)');
        $this->addSql('DROP INDEX IDX_B170F90CB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__etablissement_repertorie AS SELECT id, calque_id, type, nom, adresse, description, photo, lien, latitude, longitude FROM etablissement_repertorie');
        $this->addSql('DROP TABLE etablissement_repertorie');
        $this->addSql('CREATE TABLE etablissement_repertorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, nom VARCHAR(255) DEFAULT NULL COLLATE BINARY, adresse VARCHAR(255) DEFAULT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, photo VARCHAR(255) DEFAULT NULL COLLATE BINARY, lien VARCHAR(255) DEFAULT NULL COLLATE BINARY, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(10, 8) DEFAULT NULL, CONSTRAINT FK_B170F90CB70485B3 FOREIGN KEY (calque_id) REFERENCES calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO etablissement_repertorie (id, calque_id, type, nom, adresse, description, photo, lien, latitude, longitude) SELECT id, calque_id, type, nom, adresse, description, photo, lien, latitude, longitude FROM __temp__etablissement_repertorie');
        $this->addSql('DROP TABLE __temp__etablissement_repertorie');
        $this->addSql('CREATE INDEX IDX_B170F90CB70485B3 ON etablissement_repertorie (calque_id)');
        $this->addSql('DROP INDEX IDX_6C24F39BB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__travaux AS SELECT id, calque_id, date_debut, date_fin FROM travaux');
        $this->addSql('DROP TABLE travaux');
        $this->addSql('CREATE TABLE travaux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, CONSTRAINT FK_6C24F39BB70485B3 FOREIGN KEY (calque_id) REFERENCES calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO travaux (id, calque_id, date_debut, date_fin) SELECT id, calque_id, date_debut, date_fin FROM __temp__travaux');
        $this->addSql('DROP TABLE __temp__travaux');
        $this->addSql('CREATE INDEX IDX_6C24F39BB70485B3 ON travaux (calque_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE type_etablissment_repertorie');
        $this->addSql('DROP INDEX IDX_C4C9AE45B70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_autoroute AS SELECT id, calque_id, type, adresse, latitude, longitude FROM element_autoroute');
        $this->addSql('DROP TABLE element_autoroute');
        $this->addSql('CREATE TABLE element_autoroute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL)');
        $this->addSql('INSERT INTO element_autoroute (id, calque_id, type, adresse, latitude, longitude) SELECT id, calque_id, type, adresse, latitude, longitude FROM __temp__element_autoroute');
        $this->addSql('DROP TABLE __temp__element_autoroute');
        $this->addSql('CREATE INDEX IDX_C4C9AE45B70485B3 ON element_autoroute (calque_id)');
        $this->addSql('DROP INDEX IDX_477ED079FA20172D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_er AS SELECT id, etablissement_repertorie_id, type, adresse, latitude, longitude FROM element_er');
        $this->addSql('DROP TABLE element_er');
        $this->addSql('CREATE TABLE element_er (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, etablissement_repertorie_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL)');
        $this->addSql('INSERT INTO element_er (id, etablissement_repertorie_id, type, adresse, latitude, longitude) SELECT id, etablissement_repertorie_id, type, adresse, latitude, longitude FROM __temp__element_er');
        $this->addSql('DROP TABLE __temp__element_er');
        $this->addSql('CREATE INDEX IDX_477ED079FA20172D ON element_er (etablissement_repertorie_id)');
        $this->addSql('DROP INDEX IDX_B170F90CB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__etablissement_repertorie AS SELECT id, calque_id, type, nom, adresse, latitude, longitude, description, photo, lien FROM etablissement_repertorie');
        $this->addSql('DROP TABLE etablissement_repertorie');
        $this->addSql('CREATE TABLE etablissement_repertorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(10, 8) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, lien VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO etablissement_repertorie (id, calque_id, type, nom, adresse, latitude, longitude, description, photo, lien) SELECT id, calque_id, type, nom, adresse, latitude, longitude, description, photo, lien FROM __temp__etablissement_repertorie');
        $this->addSql('DROP TABLE __temp__etablissement_repertorie');
        $this->addSql('CREATE INDEX IDX_B170F90CB70485B3 ON etablissement_repertorie (calque_id)');
        $this->addSql('DROP INDEX IDX_6C24F39BB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__travaux AS SELECT id, calque_id, date_debut, date_fin FROM travaux');
        $this->addSql('DROP TABLE travaux');
        $this->addSql('CREATE TABLE travaux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL)');
        $this->addSql('INSERT INTO travaux (id, calque_id, date_debut, date_fin) SELECT id, calque_id, date_debut, date_fin FROM __temp__travaux');
        $this->addSql('DROP TABLE __temp__travaux');
        $this->addSql('CREATE INDEX IDX_6C24F39BB70485B3 ON travaux (calque_id)');
    }
}
