<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220117090333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commune (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_element_id INTEGER DEFAULT NULL, icone_id INTEGER DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, texte VARCHAR(255) DEFAULT NULL, lien VARCHAR(255) DEFAULT NULL, date_deb DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_41405E3921CFC01 ON element (type_element_id)');
        $this->addSql('CREATE INDEX IDX_41405E395A805D31 ON element (icone_id)');
        $this->addSql('CREATE TABLE icone (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, couleur VARCHAR(255) DEFAULT NULL, lien VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE point (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, element_id INTEGER DEFAULT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, rang SMALLINT NOT NULL)');
        $this->addSql('CREATE INDEX IDX_B7A5F3241F1F2A24 ON point (element_id)');
        $this->addSql('CREATE TABLE type_calque (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE type_element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_calque_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_696131CC3C2C4A3 ON type_element (type_calque_id)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E96C6E55B5 ON users (nom)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commune');
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE icone');
        $this->addSql('DROP TABLE point');
        $this->addSql('DROP TABLE type_calque');
        $this->addSql('DROP TABLE type_element');
        $this->addSql('DROP TABLE users');
    }
}
