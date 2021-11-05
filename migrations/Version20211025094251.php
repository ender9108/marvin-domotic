<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025094251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE protocol_parameter ADD protocol_id INT NOT NULL');
        $this->addSql('ALTER TABLE protocol_parameter ADD CONSTRAINT FK_C8897271CCD59258 FOREIGN KEY (protocol_id) REFERENCES protocol (id)');
        $this->addSql('CREATE INDEX IDX_C8897271CCD59258 ON protocol_parameter (protocol_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE protocol_parameter DROP FOREIGN KEY FK_C8897271CCD59258');
        $this->addSql('DROP INDEX IDX_C8897271CCD59258 ON protocol_parameter');
        $this->addSql('ALTER TABLE protocol_parameter DROP protocol_id');
    }
}
