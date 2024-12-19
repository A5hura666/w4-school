<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219114146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chapters (id INT AUTO_INCREMENT NOT NULL, course_id_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C721437196EF99BF (course_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, teacher_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A9A55A4C2EBB220A (teacher_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercises (id INT AUTO_INCREMENT NOT NULL, lesson_id_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FA1499135A24AD0 (lesson_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lessons (id INT AUTO_INCREMENT NOT NULL, chapter_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3F4218D9FF0D08E8 (chapter_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapters ADD CONSTRAINT FK_C721437196EF99BF FOREIGN KEY (course_id_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C2EBB220A FOREIGN KEY (teacher_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercises ADD CONSTRAINT FK_FA1499135A24AD0 FOREIGN KEY (lesson_id_id) REFERENCES lessons (id)');
        $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_3F4218D9FF0D08E8 FOREIGN KEY (chapter_id_id) REFERENCES chapters (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapters DROP FOREIGN KEY FK_C721437196EF99BF');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C2EBB220A');
        $this->addSql('ALTER TABLE exercises DROP FOREIGN KEY FK_FA1499135A24AD0');
        $this->addSql('ALTER TABLE lessons DROP FOREIGN KEY FK_3F4218D9FF0D08E8');
        $this->addSql('DROP TABLE chapters');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE exercises');
        $this->addSql('DROP TABLE lessons');
    }
}
