<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815112122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flashcard_conjugation (id INT AUTO_INCREMENT NOT NULL, polite VARCHAR(30) NOT NULL, negative VARCHAR(30) NOT NULL, conditionnal_ba VARCHAR(30) NOT NULL, conditional_tara VARCHAR(30) NOT NULL, imperative VARCHAR(30) NOT NULL, volitionnal VARCHAR(30) NOT NULL, causative VARCHAR(30) NOT NULL, potential VARCHAR(30) NOT NULL, te_form VARCHAR(30) NOT NULL, ta_form VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flashcard_grammar (id INT AUTO_INCREMENT NOT NULL, grammar_point VARCHAR(255) NOT NULL, grammar_rule VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flashcard_kanji (id INT AUTO_INCREMENT NOT NULL, onyomi VARCHAR(60) DEFAULT NULL, kunyomi VARCHAR(60) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flashcard_vocabulary (id INT AUTO_INCREMENT NOT NULL, word VARCHAR(10) NOT NULL, image VARCHAR(255) DEFAULT NULL, audio VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE flashcard_conjugation');
        $this->addSql('DROP TABLE flashcard_grammar');
        $this->addSql('DROP TABLE flashcard_kanji');
        $this->addSql('DROP TABLE flashcard_vocabulary');
    }
}
