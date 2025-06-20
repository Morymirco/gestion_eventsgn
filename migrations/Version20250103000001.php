<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial database schema for event management system';
    }

    public function up(Schema $schema): void
    {
        // Create category table
        $this->addSql('CREATE TABLE category (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            description VARCHAR(500) DEFAULT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create user table
        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL, 
            email VARCHAR(180) NOT NULL, 
            roles JSON NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            first_name VARCHAR(100) NOT NULL, 
            last_name VARCHAR(100) NOT NULL, 
            UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create event table
        $this->addSql('CREATE TABLE event (
            id INT AUTO_INCREMENT NOT NULL, 
            category_id INT NOT NULL, 
            title VARCHAR(255) NOT NULL, 
            description LONGTEXT DEFAULT NULL, 
            start_date DATETIME NOT NULL, 
            end_date DATETIME DEFAULT NULL, 
            location VARCHAR(255) DEFAULT NULL, 
            max_participants INT NOT NULL, 
            is_active TINYINT(1) NOT NULL DEFAULT 1, 
            created_at DATETIME NOT NULL, 
            INDEX IDX_3BAE0AA712469DE2 (category_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create registration table
        $this->addSql('CREATE TABLE registration (
            id INT AUTO_INCREMENT NOT NULL, 
            user_id INT NOT NULL, 
            event_id INT NOT NULL, 
            registration_date DATETIME NOT NULL, 
            is_confirmed TINYINT(1) NOT NULL DEFAULT 0, 
            notes LONGTEXT DEFAULT NULL, 
            INDEX IDX_62A8A7A7A76ED395 (user_id), 
            INDEX IDX_62A8A7A771F7E88B (event_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Add foreign key constraints
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA712469DE2');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7A76ED395');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A771F7E88B');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE user');
    }
}