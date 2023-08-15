<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815171126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_conjugation CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE flashcard_conjugation ADD CONSTRAINT FK_9A28A02EBF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_grammar CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE flashcard_grammar ADD CONSTRAINT FK_ECC26032BF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_kanji CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE flashcard_kanji ADD CONSTRAINT FK_E248AF3CBF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_vocabulary CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE flashcard_vocabulary ADD CONSTRAINT FK_716AD202BF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_conjugation DROP FOREIGN KEY FK_9A28A02EBF396750');
        $this->addSql('ALTER TABLE flashcard_conjugation CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE flashcard_grammar DROP FOREIGN KEY FK_ECC26032BF396750');
        $this->addSql('ALTER TABLE flashcard_grammar CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE flashcard_kanji DROP FOREIGN KEY FK_E248AF3CBF396750');
        $this->addSql('ALTER TABLE flashcard_kanji CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE flashcard_vocabulary DROP FOREIGN KEY FK_716AD202BF396750');
        $this->addSql('ALTER TABLE flashcard_vocabulary CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
