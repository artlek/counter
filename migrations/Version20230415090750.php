<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230415090750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE counter ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE counter_point ADD price DOUBLE PRECISION NOT NULL, DROP rate');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE counter DROP price');
        $this->addSql('ALTER TABLE counter_point ADD rate DOUBLE PRECISION DEFAULT NULL, DROP price');
    }
}
