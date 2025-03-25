<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionMerged extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE animal (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            species VARCHAR(255) NOT NULL, 
            birth_date DATETIME NOT NULL, 
            pictures_id INT DEFAULT NULL, 
            owner_id INT NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE media (
            id INT AUTO_INCREMENT NOT NULL, 
            animal_id INT DEFAULT NULL, 
            file_path VARCHAR(255) DEFAULT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL, 
            email VARCHAR(180) NOT NULL, 
            lastname VARCHAR(255) NOT NULL, 
            firstname VARCHAR(255) NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            roles JSON NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE client (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            firstname VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE traitement (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE appointment (
            id INT AUTO_INCREMENT NOT NULL, 
            animal_id INT NOT NULL, 
            assistant_id INT NOT NULL, 
            veterinarian_id INT NOT NULL, 
            created_date DATETIME NOT NULL, 
            appointment_date DATETIME NOT NULL, 
            motif LONGTEXT NOT NULL, 
            statut VARCHAR(20) NOT NULL, 
            is_paid TINYINT(1) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE appointment_traitement (
            appointment_id INT NOT NULL, 
            traitement_id INT NOT NULL, 
            PRIMARY KEY(appointment_id, traitement_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_ANIMAL_PICTURES FOREIGN KEY (pictures_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_ANIMAL_OWNER FOREIGN KEY (owner_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_MEDIA_ANIMAL FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_APPOINTMENT_ANIMAL FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_APPOINTMENT_ASSISTANT FOREIGN KEY (assistant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_APPOINTMENT_VETERINARIAN FOREIGN KEY (veterinarian_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointment_traitement ADD CONSTRAINT FK_APPOINTMENT_TREATMENT_APPOINTMENT FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment_traitement ADD CONSTRAINT FK_APPOINTMENT_TREATMENT_TREATMENT FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE CASCADE');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_EMAIL ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_MEDIA_ANIMAL ON media (animal_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ANIMAL_PICTURES ON animal (pictures_id)');
        $this->addSql('CREATE INDEX IDX_ANIMAL_OWNER ON animal (owner_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE appointment_traitement DROP FOREIGN KEY FK_APPOINTMENT_TREATMENT_APPOINTMENT');
        $this->addSql('ALTER TABLE appointment_traitement DROP FOREIGN KEY FK_APPOINTMENT_TREATMENT_TREATMENT');
        $this->addSql('DROP TABLE appointment_traitement');

        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_APPOINTMENT_ANIMAL');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_APPOINTMENT_ASSISTANT');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_APPOINTMENT_VETERINARIAN');
        $this->addSql('DROP TABLE appointment');

        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_MEDIA_ANIMAL');
        $this->addSql('DROP TABLE media');

        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_ANIMAL_PICTURES');
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_ANIMAL_OWNER');
        $this->addSql('DROP TABLE animal');

        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE traitement');
    }
}
