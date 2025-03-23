<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250323122324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add roles into the roles table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO role (name) VALUES ('ROLE_USER')");
        $this->addSql("INSERT INTO role (name) VALUES ('ROLE_ADMIN')");
        $this->addSql("INSERT INTO role (name) VALUES ('ROLE_EDITOR')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM role WHERE name IN ('ROLE_USER', 'ROLE_ADMIN', 'ROLE_EDITOR')");
    }
}