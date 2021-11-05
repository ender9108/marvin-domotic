<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103102824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_command ADD module_id INT NOT NULL');
        $this->addSql('ALTER TABLE module_command ADD CONSTRAINT FK_25B1D4CDAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_25B1D4CDAFC2B591 ON module_command (module_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_command DROP FOREIGN KEY FK_25B1D4CDAFC2B591');
        $this->addSql('DROP INDEX IDX_25B1D4CDAFC2B591 ON module_command');
        $this->addSql('ALTER TABLE module_command DROP module_id');
    }
}
