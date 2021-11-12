<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211022112356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, coordonnees_gps VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE element_autoroute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, coordonnees_gps VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE etablissement_repertorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, coordonnees_gps VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE travaux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, adresse_debut VARCHAR(255) NOT NULL, adresse_fin VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE element_autoroute');
        $this->addSql('DROP TABLE etablissement_repertorie');
        $this->addSql('DROP TABLE travaux');
    }
}
