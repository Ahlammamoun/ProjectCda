<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908143458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE certificates (id INT AUTO_INCREMENT NOT NULL, date_issued DATETIME NOT NULL, student_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_8D26FB5FCB944F1A (student_id), INDEX IDX_8D26FB5F591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, teacher_id INT NOT NULL, INDEX IDX_A9A55A4C41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE lessons (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, course_id INT NOT NULL, INDEX IDX_3F4218D9591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE quizzes (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) NOT NULL, course_id INT NOT NULL, INDEX IDX_94DC9FB5591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE certificates ADD CONSTRAINT FK_8D26FB5FCB944F1A FOREIGN KEY (student_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE certificates ADD CONSTRAINT FK_8D26FB5F591CC992 FOREIGN KEY (course_id) REFERENCES courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C41807E1D FOREIGN KEY (teacher_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_3F4218D9591CC992 FOREIGN KEY (course_id) REFERENCES courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizzes ADD CONSTRAINT FK_94DC9FB5591CC992 FOREIGN KEY (course_id) REFERENCES courses (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certificates DROP FOREIGN KEY FK_8D26FB5FCB944F1A');
        $this->addSql('ALTER TABLE certificates DROP FOREIGN KEY FK_8D26FB5F591CC992');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C41807E1D');
        $this->addSql('ALTER TABLE lessons DROP FOREIGN KEY FK_3F4218D9591CC992');
        $this->addSql('ALTER TABLE quizzes DROP FOREIGN KEY FK_94DC9FB5591CC992');
        $this->addSql('DROP TABLE certificates');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE lessons');
        $this->addSql('DROP TABLE quizzes');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
