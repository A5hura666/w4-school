<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220111212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_enrollments (id INT AUTO_INCREMENT NOT NULL, courses_id_id INT DEFAULT NULL, student_id_id INT DEFAULT NULL, anrolled_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B8B6F1E65359E06E (courses_id_id), INDEX IDX_B8B6F1E6F773E7CA (student_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_tags (id INT AUTO_INCREMENT NOT NULL, course_id_id INT DEFAULT NULL, tag_id_id INT DEFAULT NULL, INDEX IDX_A71E492096EF99BF (course_id_id), INDEX IDX_A71E49205DA88751 (tag_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson_media (id INT AUTO_INCREMENT NOT NULL, lesson_id_id INT DEFAULT NULL, media_id_id INT DEFAULT NULL, INDEX IDX_20CA758B35A24AD0 (lesson_id_id), INDEX IDX_20CA758B605D5AE6 (media_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson_progress (id INT AUTO_INCREMENT NOT NULL, lesson_id_id INT DEFAULT NULL, student_id_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6A46B85F35A24AD0 (lesson_id_id), INDEX IDX_6A46B85FF773E7CA (student_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, media_type VARCHAR(255) NOT NULL, uploaded_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, message LONGTEXT NOT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6000B0D39D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE submissions (id INT AUTO_INCREMENT NOT NULL, exercice_id_id INT DEFAULT NULL, student_id_id INT NOT NULL, submitted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', score INT NOT NULL, answer VARCHAR(255) DEFAULT NULL, INDEX IDX_3F6169F726C958BE (exercice_id_id), INDEX IDX_3F6169F7F773E7CA (student_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_enrollments ADD CONSTRAINT FK_B8B6F1E65359E06E FOREIGN KEY (courses_id_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE course_enrollments ADD CONSTRAINT FK_B8B6F1E6F773E7CA FOREIGN KEY (student_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE course_tags ADD CONSTRAINT FK_A71E492096EF99BF FOREIGN KEY (course_id_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE course_tags ADD CONSTRAINT FK_A71E49205DA88751 FOREIGN KEY (tag_id_id) REFERENCES tags (id)');
        $this->addSql('ALTER TABLE lesson_media ADD CONSTRAINT FK_20CA758B35A24AD0 FOREIGN KEY (lesson_id_id) REFERENCES lessons (id)');
        $this->addSql('ALTER TABLE lesson_media ADD CONSTRAINT FK_20CA758B605D5AE6 FOREIGN KEY (media_id_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE lesson_progress ADD CONSTRAINT FK_6A46B85F35A24AD0 FOREIGN KEY (lesson_id_id) REFERENCES lessons (id)');
        $this->addSql('ALTER TABLE lesson_progress ADD CONSTRAINT FK_6A46B85FF773E7CA FOREIGN KEY (student_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE submissions ADD CONSTRAINT FK_3F6169F726C958BE FOREIGN KEY (exercice_id_id) REFERENCES exercises (id)');
        $this->addSql('ALTER TABLE submissions ADD CONSTRAINT FK_3F6169F7F773E7CA FOREIGN KEY (student_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_enrollments DROP FOREIGN KEY FK_B8B6F1E65359E06E');
        $this->addSql('ALTER TABLE course_enrollments DROP FOREIGN KEY FK_B8B6F1E6F773E7CA');
        $this->addSql('ALTER TABLE course_tags DROP FOREIGN KEY FK_A71E492096EF99BF');
        $this->addSql('ALTER TABLE course_tags DROP FOREIGN KEY FK_A71E49205DA88751');
        $this->addSql('ALTER TABLE lesson_media DROP FOREIGN KEY FK_20CA758B35A24AD0');
        $this->addSql('ALTER TABLE lesson_media DROP FOREIGN KEY FK_20CA758B605D5AE6');
        $this->addSql('ALTER TABLE lesson_progress DROP FOREIGN KEY FK_6A46B85F35A24AD0');
        $this->addSql('ALTER TABLE lesson_progress DROP FOREIGN KEY FK_6A46B85FF773E7CA');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D39D86650F');
        $this->addSql('ALTER TABLE submissions DROP FOREIGN KEY FK_3F6169F726C958BE');
        $this->addSql('ALTER TABLE submissions DROP FOREIGN KEY FK_3F6169F7F773E7CA');
        $this->addSql('DROP TABLE course_enrollments');
        $this->addSql('DROP TABLE course_tags');
        $this->addSql('DROP TABLE lesson_media');
        $this->addSql('DROP TABLE lesson_progress');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE submissions');
        $this->addSql('DROP TABLE tags');
    }
}
