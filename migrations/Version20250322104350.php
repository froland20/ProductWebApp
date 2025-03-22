<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250322104350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set the length of the name column in the currency table to VARCHAR(32)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE currency MODIFY name VARCHAR(32) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE currency MODIFY name VARCHAR(3) NOT NULL');
    }
}
