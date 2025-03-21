<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321085126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, specie VARCHAR(255) NOT NULL, born_date DATE NOT NULL, INDEX IDX_6AAB231F19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, animal_id INT DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_6A2CA10C8E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE treatment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, duration VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visit (id INT AUTO_INCREMENT NOT NULL, assistant_id INT NOT NULL, veterinarian_id INT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_visit DATE NOT NULL, reason VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_437EE939E05387EF (assistant_id), INDEX IDX_437EE939804C8213 (veterinarian_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visit_treatment (visit_id INT NOT NULL, treatment_id INT NOT NULL, INDEX IDX_1BD7EF2875FA0FF2 (visit_id), INDEX IDX_1BD7EF28471C0366 (treatment_id), PRIMARY KEY(visit_id, treatment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939E05387EF FOREIGN KEY (assistant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939804C8213 FOREIGN KEY (veterinarian_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE visit_treatment ADD CONSTRAINT FK_1BD7EF2875FA0FF2 FOREIGN KEY (visit_id) REFERENCES visit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visit_treatment ADD CONSTRAINT FK_1BD7EF28471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231F19EB6921');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C8E962C16');
        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE939E05387EF');
        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE939804C8213');
        $this->addSql('ALTER TABLE visit_treatment DROP FOREIGN KEY FK_1BD7EF2875FA0FF2');
        $this->addSql('ALTER TABLE visit_treatment DROP FOREIGN KEY FK_1BD7EF28471C0366');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE treatment');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE visit');
        $this->addSql('DROP TABLE visit_treatment');
    }
}
