<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028141759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module ADD protocol_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628CCD59258 FOREIGN KEY (protocol_id) REFERENCES protocol (id)');
        $this->addSql('CREATE INDEX IDX_C242628CCD59258 ON module (protocol_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628CCD59258');
        $this->addSql('DROP INDEX IDX_C242628CCD59258 ON module');
        $this->addSql('ALTER TABLE module DROP protocol_id');
    }
}
