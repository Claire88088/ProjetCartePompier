<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211125124736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('DROP INDEX IDX_C4C9AE4584C36B66');
        $this->addSql('DROP INDEX IDX_C4C9AE45B70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_autoroute AS SELECT id, calque_id, type_element_autoroute_id, latitude, longitude FROM element_autoroute');
        $this->addSql('DROP TABLE element_autoroute');
        $this->addSql('CREATE TABLE element_autoroute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type_element_autoroute_id INTEGER DEFAULT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL, CONSTRAINT FK_C4C9AE45B70485B3 FOREIGN KEY (calque_id) REFERENCES calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C4C9AE4584C36B66 FOREIGN KEY (type_element_autoroute_id) REFERENCES type_element_autoroute (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO element_autoroute (id, calque_id, type_element_autoroute_id, latitude, longitude) SELECT id, calque_id, type_element_autoroute_id, latitude, longitude FROM __temp__element_autoroute');
        $this->addSql('DROP TABLE __temp__element_autoroute');
        $this->addSql('CREATE INDEX IDX_C4C9AE4584C36B66 ON element_autoroute (type_element_autoroute_id)');
        $this->addSql('CREATE INDEX IDX_C4C9AE45B70485B3 ON element_autoroute (calque_id)');
        $this->addSql('DROP INDEX IDX_477ED0797B09B8B9');
        $this->addSql('DROP INDEX IDX_477ED079FA20172D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_er AS SELECT id, etablissement_repertorie_id, type_element_er_id, latitude, longitude FROM element_er');
        $this->addSql('DROP TABLE element_er');
        $this->addSql('CREATE TABLE element_er (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, etablissement_repertorie_id INTEGER DEFAULT NULL, type_element_er_id INTEGER DEFAULT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL, CONSTRAINT FK_477ED079FA20172D FOREIGN KEY (etablissement_repertorie_id) REFERENCES etablissement_repertorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_477ED0797B09B8B9 FOREIGN KEY (type_element_er_id) REFERENCES type_element_er (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO element_er (id, etablissement_repertorie_id, type_element_er_id, latitude, longitude) SELECT id, etablissement_repertorie_id, type_element_er_id, latitude, longitude FROM __temp__element_er');
        $this->addSql('DROP TABLE __temp__element_er');
        $this->addSql('CREATE INDEX IDX_477ED0797B09B8B9 ON element_er (type_element_er_id)');
        $this->addSql('CREATE INDEX IDX_477ED079FA20172D ON element_er (etablissement_repertorie_id)');
        $this->addSql('DROP INDEX IDX_B170F90CEF3FB646');
        $this->addSql('DROP INDEX IDX_B170F90CB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__etablissement_repertorie AS SELECT id, calque_id, type_etablissment_repertorie_id, description, photo, lien FROM etablissement_repertorie');
        $this->addSql('DROP TABLE etablissement_repertorie');
        $this->addSql('CREATE TABLE etablissement_repertorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type_etablissment_repertorie_id INTEGER DEFAULT NULL, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, photo VARCHAR(255) DEFAULT NULL COLLATE BINARY, lien VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_B170F90CB70485B3 FOREIGN KEY (calque_id) REFERENCES calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B170F90CEF3FB646 FOREIGN KEY (type_etablissment_repertorie_id) REFERENCES type_etablissment_repertorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO etablissement_repertorie (id, calque_id, type_etablissment_repertorie_id, description, photo, lien) SELECT id, calque_id, type_etablissment_repertorie_id, description, photo, lien FROM __temp__etablissement_repertorie');
        $this->addSql('DROP TABLE __temp__etablissement_repertorie');
        $this->addSql('CREATE INDEX IDX_B170F90CEF3FB646 ON etablissement_repertorie (type_etablissment_repertorie_id)');
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
        $this->addSql('DROP TABLE type_element');
        $this->addSql('DROP INDEX IDX_C4C9AE45B70485B3');
        $this->addSql('DROP INDEX IDX_C4C9AE4584C36B66');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_autoroute AS SELECT id, calque_id, type_element_autoroute_id, latitude, longitude FROM element_autoroute');
        $this->addSql('DROP TABLE element_autoroute');
        $this->addSql('CREATE TABLE element_autoroute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type_element_autoroute_id INTEGER DEFAULT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL)');
        $this->addSql('INSERT INTO element_autoroute (id, calque_id, type_element_autoroute_id, latitude, longitude) SELECT id, calque_id, type_element_autoroute_id, latitude, longitude FROM __temp__element_autoroute');
        $this->addSql('DROP TABLE __temp__element_autoroute');
        $this->addSql('CREATE INDEX IDX_C4C9AE45B70485B3 ON element_autoroute (calque_id)');
        $this->addSql('CREATE INDEX IDX_C4C9AE4584C36B66 ON element_autoroute (type_element_autoroute_id)');
        $this->addSql('DROP INDEX IDX_477ED079FA20172D');
        $this->addSql('DROP INDEX IDX_477ED0797B09B8B9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element_er AS SELECT id, etablissement_repertorie_id, type_element_er_id, latitude, longitude FROM element_er');
        $this->addSql('DROP TABLE element_er');
        $this->addSql('CREATE TABLE element_er (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, etablissement_repertorie_id INTEGER DEFAULT NULL, type_element_er_id INTEGER DEFAULT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL)');
        $this->addSql('INSERT INTO element_er (id, etablissement_repertorie_id, type_element_er_id, latitude, longitude) SELECT id, etablissement_repertorie_id, type_element_er_id, latitude, longitude FROM __temp__element_er');
        $this->addSql('DROP TABLE __temp__element_er');
        $this->addSql('CREATE INDEX IDX_477ED079FA20172D ON element_er (etablissement_repertorie_id)');
        $this->addSql('CREATE INDEX IDX_477ED0797B09B8B9 ON element_er (type_element_er_id)');
        $this->addSql('DROP INDEX IDX_B170F90CB70485B3');
        $this->addSql('DROP INDEX IDX_B170F90CEF3FB646');
        $this->addSql('CREATE TEMPORARY TABLE __temp__etablissement_repertorie AS SELECT id, calque_id, type_etablissment_repertorie_id, description, photo, lien FROM etablissement_repertorie');
        $this->addSql('DROP TABLE etablissement_repertorie');
        $this->addSql('CREATE TABLE etablissement_repertorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, type_etablissment_repertorie_id INTEGER DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, lien VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO etablissement_repertorie (id, calque_id, type_etablissment_repertorie_id, description, photo, lien) SELECT id, calque_id, type_etablissment_repertorie_id, description, photo, lien FROM __temp__etablissement_repertorie');
        $this->addSql('DROP TABLE __temp__etablissement_repertorie');
        $this->addSql('CREATE INDEX IDX_B170F90CB70485B3 ON etablissement_repertorie (calque_id)');
        $this->addSql('CREATE INDEX IDX_B170F90CEF3FB646 ON etablissement_repertorie (type_etablissment_repertorie_id)');
        $this->addSql('DROP INDEX IDX_6C24F39BB70485B3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__travaux AS SELECT id, calque_id, date_debut, date_fin FROM travaux');
        $this->addSql('DROP TABLE travaux');
        $this->addSql('CREATE TABLE travaux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, calque_id INTEGER DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL)');
        $this->addSql('INSERT INTO travaux (id, calque_id, date_debut, date_fin) SELECT id, calque_id, date_debut, date_fin FROM __temp__travaux');
        $this->addSql('DROP TABLE __temp__travaux');
        $this->addSql('CREATE INDEX IDX_6C24F39BB70485B3 ON travaux (calque_id)');
    }
}
