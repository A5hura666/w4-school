<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241221133948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapters DROP FOREIGN KEY FK_C721437196EF99BF');
        $this->addSql('DROP INDEX IDX_C721437196EF99BF ON chapters');
        $this->addSql('ALTER TABLE chapters CHANGE course_id_id course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chapters ADD CONSTRAINT FK_C7214371591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('CREATE INDEX IDX_C7214371591CC992 ON chapters (course_id)');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C2EBB220A');
        $this->addSql('DROP INDEX IDX_A9A55A4C2EBB220A ON courses');
        $this->addSql('ALTER TABLE courses CHANGE teacher_id_id teacher_id INT NOT NULL');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A9A55A4C41807E1D ON courses (teacher_id)');
        $this->addSql('ALTER TABLE exercises DROP FOREIGN KEY FK_FA1499135A24AD0');
        $this->addSql('DROP INDEX IDX_FA1499135A24AD0 ON exercises');
        $this->addSql('ALTER TABLE exercises CHANGE lesson_id_id lesson_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercises ADD CONSTRAINT FK_FA14991CDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
        $this->addSql('CREATE INDEX IDX_FA14991CDF80196 ON exercises (lesson_id)');
        $this->addSql('ALTER TABLE lessons DROP FOREIGN KEY FK_3F4218D9FF0D08E8');
        $this->addSql('DROP INDEX IDX_3F4218D9FF0D08E8 ON lessons');
        $this->addSql('ALTER TABLE lessons CHANGE chapter_id_id chapter_id INT NOT NULL');
        $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_3F4218D9579F4768 FOREIGN KEY (chapter_id) REFERENCES chapters (id)');
        $this->addSql('CREATE INDEX IDX_3F4218D9579F4768 ON lessons (chapter_id)');
        $this->addSql('ALTER TABLE submissions DROP FOREIGN KEY FK_3F6169F726C958BE');
        $this->addSql('DROP INDEX IDX_3F6169F726C958BE ON submissions');
        $this->addSql('ALTER TABLE submissions CHANGE exercice_id_id exercice_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE submissions ADD CONSTRAINT FK_3F6169F789D40298 FOREIGN KEY (exercice_id) REFERENCES exercises (id)');
        $this->addSql('CREATE INDEX IDX_3F6169F789D40298 ON submissions (exercice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapters DROP FOREIGN KEY FK_C7214371591CC992');
        $this->addSql('DROP INDEX IDX_C7214371591CC992 ON chapters');
        $this->addSql('ALTER TABLE chapters CHANGE course_id course_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chapters ADD CONSTRAINT FK_C721437196EF99BF FOREIGN KEY (course_id_id) REFERENCES courses (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C721437196EF99BF ON chapters (course_id_id)');
        $this->addSql('ALTER TABLE exercises DROP FOREIGN KEY FK_FA14991CDF80196');
        $this->addSql('DROP INDEX IDX_FA14991CDF80196 ON exercises');
        $this->addSql('ALTER TABLE exercises CHANGE lesson_id lesson_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercises ADD CONSTRAINT FK_FA1499135A24AD0 FOREIGN KEY (lesson_id_id) REFERENCES lessons (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FA1499135A24AD0 ON exercises (lesson_id_id)');
        $this->addSql('ALTER TABLE lessons DROP FOREIGN KEY FK_3F4218D9579F4768');
        $this->addSql('DROP INDEX IDX_3F4218D9579F4768 ON lessons');
        $this->addSql('ALTER TABLE lessons CHANGE chapter_id chapter_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_3F4218D9FF0D08E8 FOREIGN KEY (chapter_id_id) REFERENCES chapters (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3F4218D9FF0D08E8 ON lessons (chapter_id_id)');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C41807E1D');
        $this->addSql('DROP INDEX IDX_A9A55A4C41807E1D ON courses');
        $this->addSql('ALTER TABLE courses CHANGE teacher_id teacher_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C2EBB220A FOREIGN KEY (teacher_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A9A55A4C2EBB220A ON courses (teacher_id_id)');
        $this->addSql('ALTER TABLE submissions DROP FOREIGN KEY FK_3F6169F789D40298');
        $this->addSql('DROP INDEX IDX_3F6169F789D40298 ON submissions');
        $this->addSql('ALTER TABLE submissions CHANGE exercice_id exercice_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE submissions ADD CONSTRAINT FK_3F6169F726C958BE FOREIGN KEY (exercice_id_id) REFERENCES exercises (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3F6169F726C958BE ON submissions (exercice_id_id)');
    }
}
