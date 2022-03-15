<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220315080403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_41405E395A805D31');
        $this->addSql('DROP INDEX IDX_41405E3921CFC01');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element AS SELECT id, type_element_id, icone_id, photo, texte, lien, date_deb, date_fin, nom, couleur FROM element');
        $this->addSql('DROP TABLE element');
        $this->addSql('CREATE TABLE element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_element_id INTEGER DEFAULT NULL, icone_id INTEGER DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, texte VARCHAR(255) DEFAULT NULL, lien VARCHAR(255) DEFAULT NULL, date_deb DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, couleur VARCHAR(7) DEFAULT NULL, CONSTRAINT FK_41405E3921CFC01 FOREIGN KEY (type_element_id) REFERENCES type_element (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_41405E395A805D31 FOREIGN KEY (icone_id) REFERENCES icone (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO element (id, type_element_id, icone_id, photo, texte, lien, date_deb, date_fin, nom, couleur) SELECT id, type_element_id, icone_id, photo, texte, lien, date_deb, date_fin, nom, couleur FROM __temp__element');
        $this->addSql('DROP TABLE __temp__element');
        $this->addSql('CREATE INDEX IDX_41405E395A805D31 ON element (icone_id)');
        $this->addSql('CREATE INDEX IDX_41405E3921CFC01 ON element (type_element_id)');
        $this->addSql('DROP INDEX IDX_B7A5F3241F1F2A24');
        $this->addSql('CREATE TEMPORARY TABLE __temp__point AS SELECT id, element_id, longitude, latitude, rang FROM point');
        $this->addSql('DROP TABLE point');
        $this->addSql('CREATE TABLE point (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, element_id INTEGER DEFAULT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, rang SMALLINT NOT NULL, CONSTRAINT FK_B7A5F3241F1F2A24 FOREIGN KEY (element_id) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO point (id, element_id, longitude, latitude, rang) SELECT id, element_id, longitude, latitude, rang FROM __temp__point');
        $this->addSql('DROP TABLE __temp__point');
        $this->addSql('CREATE INDEX IDX_B7A5F3241F1F2A24 ON point (element_id)');
        $this->addSql('DROP INDEX IDX_696131CC3C2C4A3');
        $this->addSql('DROP INDEX UNIQ_696131CC6C6E55B5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__type_element AS SELECT id, type_calque_id, nom, type FROM type_element');
        $this->addSql('DROP TABLE type_element');
        $this->addSql('CREATE TABLE type_element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_calque_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, CONSTRAINT FK_696131CC3C2C4A3 FOREIGN KEY (type_calque_id) REFERENCES type_calque (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO type_element (id, type_calque_id, nom, type) SELECT id, type_calque_id, nom, type FROM __temp__type_element');
        $this->addSql('DROP TABLE __temp__type_element');
        $this->addSql('CREATE INDEX IDX_696131CC3C2C4A3 ON type_element (type_calque_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_696131CC6C6E55B5 ON type_element (nom)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_41405E3921CFC01');
        $this->addSql('DROP INDEX IDX_41405E395A805D31');
        $this->addSql('CREATE TEMPORARY TABLE __temp__element AS SELECT id, type_element_id, icone_id, nom, photo, texte, lien, date_deb, date_fin, couleur FROM element');
        $this->addSql('DROP TABLE element');
        $this->addSql('CREATE TABLE element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_element_id INTEGER DEFAULT NULL, icone_id INTEGER DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, texte VARCHAR(255) DEFAULT NULL, lien VARCHAR(255) DEFAULT NULL, date_deb DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, couleur VARCHAR(7) DEFAULT NULL)');
        $this->addSql('INSERT INTO element (id, type_element_id, icone_id, nom, photo, texte, lien, date_deb, date_fin, couleur) SELECT id, type_element_id, icone_id, nom, photo, texte, lien, date_deb, date_fin, couleur FROM __temp__element');
        $this->addSql('DROP TABLE __temp__element');
        $this->addSql('CREATE INDEX IDX_41405E3921CFC01 ON element (type_element_id)');
        $this->addSql('CREATE INDEX IDX_41405E395A805D31 ON element (icone_id)');
        $this->addSql('DROP INDEX IDX_B7A5F3241F1F2A24');
        $this->addSql('CREATE TEMPORARY TABLE __temp__point AS SELECT id, element_id, longitude, latitude, rang FROM point');
        $this->addSql('DROP TABLE point');
        $this->addSql('CREATE TABLE point (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, element_id INTEGER DEFAULT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, rang SMALLINT NOT NULL)');
        $this->addSql('INSERT INTO point (id, element_id, longitude, latitude, rang) SELECT id, element_id, longitude, latitude, rang FROM __temp__point');
        $this->addSql('DROP TABLE __temp__point');
        $this->addSql('CREATE INDEX IDX_B7A5F3241F1F2A24 ON point (element_id)');
        $this->addSql('DROP INDEX UNIQ_696131CC6C6E55B5');
        $this->addSql('DROP INDEX IDX_696131CC3C2C4A3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__type_element AS SELECT id, type_calque_id, nom, type FROM type_element');
        $this->addSql('DROP TABLE type_element');
        $this->addSql('CREATE TABLE type_element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_calque_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO type_element (id, type_calque_id, nom, type) SELECT id, type_calque_id, nom, type FROM __temp__type_element');
        $this->addSql('DROP TABLE __temp__type_element');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_696131CC6C6E55B5 ON type_element (nom)');
        $this->addSql('CREATE INDEX IDX_696131CC3C2C4A3 ON type_element (type_calque_id)');
    }
}
