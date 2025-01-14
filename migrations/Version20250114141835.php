<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114141835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_media DROP FOREIGN KEY FK_20CA758BCDF80196');
        $this->addSql('ALTER TABLE lesson_media DROP FOREIGN KEY FK_20CA758BEA9FDD75');
        $this->addSql('DROP TABLE lesson_media');
        $this->addSql('ALTER TABLE media ADD related_id INT DEFAULT NULL, ADD related_type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_media (id INT AUTO_INCREMENT NOT NULL, lesson_id INT DEFAULT NULL, media_id INT DEFAULT NULL, INDEX IDX_20CA758BCDF80196 (lesson_id), INDEX IDX_20CA758BEA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lesson_media ADD CONSTRAINT FK_20CA758BCDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE lesson_media ADD CONSTRAINT FK_20CA758BEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE media DROP related_id, DROP related_type');
    }
}
