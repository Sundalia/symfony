<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220725161620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE page_count page_count INT DEFAULT NULL, CHANGE thumbnail_url thumbnail_url VARCHAR(1000) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE title title VARCHAR(255) NOT NULL, CHANGE page_count page_count INT NOT NULL, CHANGE thumbnail_url thumbnail_url VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL');
    }
}
