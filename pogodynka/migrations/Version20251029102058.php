<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251029102058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__measurement AS SELECT id, location_id, date, celcius, rainfall, wind_speed, humidity FROM measurement');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('CREATE TABLE measurement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, location_id INTEGER NOT NULL, date DATE NOT NULL, celcius DOUBLE PRECISION NOT NULL, rainfall DOUBLE PRECISION NOT NULL, wind_speed DOUBLE PRECISION NOT NULL, humidity DOUBLE PRECISION NOT NULL, CONSTRAINT FK_2CE0D81164D218E FOREIGN KEY (location_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO measurement (id, location_id, date, celcius, rainfall, wind_speed, humidity) SELECT id, location_id, date, celcius, rainfall, wind_speed, humidity FROM __temp__measurement');
        $this->addSql('DROP TABLE __temp__measurement');
        $this->addSql('CREATE INDEX IDX_2CE0D81164D218E ON measurement (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__measurement AS SELECT id, location_id, date, celcius, rainfall, wind_speed, humidity FROM measurement');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('CREATE TABLE measurement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, location_id INTEGER NOT NULL, date DATE NOT NULL, celcius NUMERIC(3, 0) NOT NULL, rainfall VARCHAR(255) NOT NULL, wind_speed DOUBLE PRECISION NOT NULL, humidity DOUBLE PRECISION NOT NULL, CONSTRAINT FK_2CE0D81164D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO measurement (id, location_id, date, celcius, rainfall, wind_speed, humidity) SELECT id, location_id, date, celcius, rainfall, wind_speed, humidity FROM __temp__measurement');
        $this->addSql('DROP TABLE __temp__measurement');
        $this->addSql('CREATE INDEX IDX_2CE0D81164D218E ON measurement (location_id)');
    }
}
