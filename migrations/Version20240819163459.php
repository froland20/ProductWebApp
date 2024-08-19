<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819163459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'User table add first_name, last_name, gender columns in a specific order.';
    }

    public function up(Schema $schema): void
    {
        // Adding columns with specific order using MySQL syntax
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL AFTER email');
        $this->addSql('ALTER TABLE user ADD last_name VARCHAR(255) NOT NULL AFTER first_name');
        $this->addSql('ALTER TABLE user ADD gender VARCHAR(10) NOT NULL AFTER last_name');
    }

    public function down(Schema $schema): void
    {
        // Dropping columns in reverse order to maintain schema integrity
        $this->addSql('ALTER TABLE user DROP gender');
        $this->addSql('ALTER TABLE user DROP last_name');
        $this->addSql('ALTER TABLE user DROP first_name');
    }
}
