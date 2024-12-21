<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241221134500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_enrollments DROP FOREIGN KEY FK_B8B6F1E65359E06E');
        $this->addSql('ALTER TABLE course_enrollments DROP FOREIGN KEY FK_B8B6F1E6F773E7CA');
        $this->addSql('DROP INDEX IDX_B8B6F1E65359E06E ON course_enrollments');
        $this->addSql('DROP INDEX IDX_B8B6F1E6F773E7CA ON course_enrollments');
        $this->addSql('ALTER TABLE course_enrollments ADD courses_id INT DEFAULT NULL, ADD student_id INT DEFAULT NULL, DROP courses_id_id, DROP student_id_id');
        $this->addSql('ALTER TABLE course_enrollments ADD CONSTRAINT FK_B8B6F1E6F9295384 FOREIGN KEY (courses_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE course_enrollments ADD CONSTRAINT FK_B8B6F1E6CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B8B6F1E6F9295384 ON course_enrollments (courses_id)');
        $this->addSql('CREATE INDEX IDX_B8B6F1E6CB944F1A ON course_enrollments (student_id)');
        $this->addSql('ALTER TABLE course_tags DROP FOREIGN KEY FK_A71E49205DA88751');
        $this->addSql('ALTER TABLE course_tags DROP FOREIGN KEY FK_A71E492096EF99BF');
        $this->addSql('DROP INDEX IDX_A71E492096EF99BF ON course_tags');
        $this->addSql('DROP INDEX IDX_A71E49205DA88751 ON course_tags');
        $this->addSql('ALTER TABLE course_tags ADD course_id INT DEFAULT NULL, ADD tag_id INT DEFAULT NULL, DROP course_id_id, DROP tag_id_id');
        $this->addSql('ALTER TABLE course_tags ADD CONSTRAINT FK_A71E4920591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE course_tags ADD CONSTRAINT FK_A71E4920BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
        $this->addSql('CREATE INDEX IDX_A71E4920591CC992 ON course_tags (course_id)');
        $this->addSql('CREATE INDEX IDX_A71E4920BAD26311 ON course_tags (tag_id)');
        $this->addSql('ALTER TABLE lesson_media DROP FOREIGN KEY FK_20CA758B35A24AD0');
        $this->addSql('ALTER TABLE lesson_media DROP FOREIGN KEY FK_20CA758B605D5AE6');
        $this->addSql('DROP INDEX IDX_20CA758B605D5AE6 ON lesson_media');
        $this->addSql('DROP INDEX IDX_20CA758B35A24AD0 ON lesson_media');
        $this->addSql('ALTER TABLE lesson_media ADD lesson_id INT DEFAULT NULL, ADD media_id INT DEFAULT NULL, DROP lesson_id_id, DROP media_id_id');
        $this->addSql('ALTER TABLE lesson_media ADD CONSTRAINT FK_20CA758BCDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
        $this->addSql('ALTER TABLE lesson_media ADD CONSTRAINT FK_20CA758BEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('CREATE INDEX IDX_20CA758BCDF80196 ON lesson_media (lesson_id)');
        $this->addSql('CREATE INDEX IDX_20CA758BEA9FDD75 ON lesson_media (media_id)');
        $this->addSql('ALTER TABLE lesson_progress DROP FOREIGN KEY FK_6A46B85F35A24AD0');
        $this->addSql('ALTER TABLE lesson_progress DROP FOREIGN KEY FK_6A46B85FF773E7CA');
        $this->addSql('DROP INDEX IDX_6A46B85F35A24AD0 ON lesson_progress');
        $this->addSql('DROP INDEX IDX_6A46B85FF773E7CA ON lesson_progress');
        $this->addSql('ALTER TABLE lesson_progress ADD lesson_id INT DEFAULT NULL, ADD student_id INT DEFAULT NULL, DROP lesson_id_id, DROP student_id_id');
        $this->addSql('ALTER TABLE lesson_progress ADD CONSTRAINT FK_6A46B85FCDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
        $this->addSql('ALTER TABLE lesson_progress ADD CONSTRAINT FK_6A46B85FCB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6A46B85FCDF80196 ON lesson_progress (lesson_id)');
        $this->addSql('CREATE INDEX IDX_6A46B85FCB944F1A ON lesson_progress (student_id)');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D39D86650F');
        $this->addSql('DROP INDEX IDX_6000B0D39D86650F ON notifications');
        $this->addSql('ALTER TABLE notifications CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6000B0D3A76ED395 ON notifications (user_id)');
        $this->addSql('ALTER TABLE submissions DROP FOREIGN KEY FK_3F6169F7F773E7CA');
        $this->addSql('DROP INDEX IDX_3F6169F7F773E7CA ON submissions');
        $this->addSql('ALTER TABLE submissions CHANGE student_id_id student_id INT NOT NULL');
        $this->addSql('ALTER TABLE submissions ADD CONSTRAINT FK_3F6169F7CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3F6169F7CB944F1A ON submissions (student_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_media DROP FOREIGN KEY FK_20CA758BCDF80196');
        $this->addSql('ALTER TABLE lesson_media DROP FOREIGN KEY FK_20CA758BEA9FDD75');
        $this->addSql('DROP INDEX IDX_20CA758BCDF80196 ON lesson_media');
        $this->addSql('DROP INDEX IDX_20CA758BEA9FDD75 ON lesson_media');
        $this->addSql('ALTER TABLE lesson_media ADD lesson_id_id INT DEFAULT NULL, ADD media_id_id INT DEFAULT NULL, DROP lesson_id, DROP media_id');
        $this->addSql('ALTER TABLE lesson_media ADD CONSTRAINT FK_20CA758B35A24AD0 FOREIGN KEY (lesson_id_id) REFERENCES lessons (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE lesson_media ADD CONSTRAINT FK_20CA758B605D5AE6 FOREIGN KEY (media_id_id) REFERENCES media (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_20CA758B605D5AE6 ON lesson_media (media_id_id)');
        $this->addSql('CREATE INDEX IDX_20CA758B35A24AD0 ON lesson_media (lesson_id_id)');
        $this->addSql('ALTER TABLE lesson_progress DROP FOREIGN KEY FK_6A46B85FCDF80196');
        $this->addSql('ALTER TABLE lesson_progress DROP FOREIGN KEY FK_6A46B85FCB944F1A');
        $this->addSql('DROP INDEX IDX_6A46B85FCDF80196 ON lesson_progress');
        $this->addSql('DROP INDEX IDX_6A46B85FCB944F1A ON lesson_progress');
        $this->addSql('ALTER TABLE lesson_progress ADD lesson_id_id INT DEFAULT NULL, ADD student_id_id INT DEFAULT NULL, DROP lesson_id, DROP student_id');
        $this->addSql('ALTER TABLE lesson_progress ADD CONSTRAINT FK_6A46B85F35A24AD0 FOREIGN KEY (lesson_id_id) REFERENCES lessons (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE lesson_progress ADD CONSTRAINT FK_6A46B85FF773E7CA FOREIGN KEY (student_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6A46B85F35A24AD0 ON lesson_progress (lesson_id_id)');
        $this->addSql('CREATE INDEX IDX_6A46B85FF773E7CA ON lesson_progress (student_id_id)');
        $this->addSql('ALTER TABLE course_tags DROP FOREIGN KEY FK_A71E4920591CC992');
        $this->addSql('ALTER TABLE course_tags DROP FOREIGN KEY FK_A71E4920BAD26311');
        $this->addSql('DROP INDEX IDX_A71E4920591CC992 ON course_tags');
        $this->addSql('DROP INDEX IDX_A71E4920BAD26311 ON course_tags');
        $this->addSql('ALTER TABLE course_tags ADD course_id_id INT DEFAULT NULL, ADD tag_id_id INT DEFAULT NULL, DROP course_id, DROP tag_id');
        $this->addSql('ALTER TABLE course_tags ADD CONSTRAINT FK_A71E49205DA88751 FOREIGN KEY (tag_id_id) REFERENCES tags (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE course_tags ADD CONSTRAINT FK_A71E492096EF99BF FOREIGN KEY (course_id_id) REFERENCES courses (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A71E492096EF99BF ON course_tags (course_id_id)');
        $this->addSql('CREATE INDEX IDX_A71E49205DA88751 ON course_tags (tag_id_id)');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('DROP INDEX IDX_6000B0D3A76ED395 ON notifications');
        $this->addSql('ALTER TABLE notifications CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6000B0D39D86650F ON notifications (user_id_id)');
        $this->addSql('ALTER TABLE submissions DROP FOREIGN KEY FK_3F6169F7CB944F1A');
        $this->addSql('DROP INDEX IDX_3F6169F7CB944F1A ON submissions');
        $this->addSql('ALTER TABLE submissions CHANGE student_id student_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE submissions ADD CONSTRAINT FK_3F6169F7F773E7CA FOREIGN KEY (student_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3F6169F7F773E7CA ON submissions (student_id_id)');
        $this->addSql('ALTER TABLE course_enrollments DROP FOREIGN KEY FK_B8B6F1E6F9295384');
        $this->addSql('ALTER TABLE course_enrollments DROP FOREIGN KEY FK_B8B6F1E6CB944F1A');
        $this->addSql('DROP INDEX IDX_B8B6F1E6F9295384 ON course_enrollments');
        $this->addSql('DROP INDEX IDX_B8B6F1E6CB944F1A ON course_enrollments');
        $this->addSql('ALTER TABLE course_enrollments ADD courses_id_id INT DEFAULT NULL, ADD student_id_id INT DEFAULT NULL, DROP courses_id, DROP student_id');
        $this->addSql('ALTER TABLE course_enrollments ADD CONSTRAINT FK_B8B6F1E65359E06E FOREIGN KEY (courses_id_id) REFERENCES courses (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE course_enrollments ADD CONSTRAINT FK_B8B6F1E6F773E7CA FOREIGN KEY (student_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B8B6F1E65359E06E ON course_enrollments (courses_id_id)');
        $this->addSql('CREATE INDEX IDX_B8B6F1E6F773E7CA ON course_enrollments (student_id_id)');
    }
}
