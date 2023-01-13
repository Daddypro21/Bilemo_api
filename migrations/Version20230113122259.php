<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230113122259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE configuration (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, color VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, memory DOUBLE PRECISION NOT NULL, INDEX IDX_A5E2A5D74584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_C53D045F4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, userr_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, screen DOUBLE PRECISION NOT NULL, weight DOUBLE PRECISION NOT NULL, length DOUBLE PRECISION NOT NULL, width DOUBLE PRECISION NOT NULL, height DOUBLE PRECISION NOT NULL, wifi TINYINT(1) NOT NULL, video TINYINT(1) NOT NULL, bluetooth TINYINT(1) NOT NULL, camera TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D34A04ADDF0FD358 (userr_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_customer (id INT AUTO_INCREMENT NOT NULL, userr_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_61B46A09DF0FD358 (userr_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE configuration ADD CONSTRAINT FK_A5E2A5D74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADDF0FD358 FOREIGN KEY (userr_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_customer ADD CONSTRAINT FK_61B46A09DF0FD358 FOREIGN KEY (userr_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE configuration DROP FOREIGN KEY FK_A5E2A5D74584665A');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADDF0FD358');
        $this->addSql('ALTER TABLE user_customer DROP FOREIGN KEY FK_61B46A09DF0FD358');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_customer');
    }
}
