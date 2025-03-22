<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250322104625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Default data with name and symbol into the currency table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO currency (name, symbol) VALUES ('Forint', 'Ft')");
        $this->addSql("INSERT INTO currency (name, symbol) VALUES ('Euro', '€')");
        $this->addSql("INSERT INTO currency (name, symbol) VALUES ('Dollár', '$')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM currency WHERE name IN ('Forint', 'Euro', 'Dollár')");
    }
}
