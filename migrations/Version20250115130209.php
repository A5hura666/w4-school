<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115130209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C5926566C');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C5926566C FOREIGN KEY (illustration_id) REFERENCES media (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C5926566C');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C5926566C FOREIGN KEY (illustration_id) REFERENCES media (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
