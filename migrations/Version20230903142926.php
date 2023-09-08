<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230903142926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_kanji ADD kanji VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE flashcard_modification ADD deck_id INT NOT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE flashcard_id flashcard_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE flashcard_modification ADD CONSTRAINT FK_9BFCCF72111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('CREATE INDEX IDX_9BFCCF72111948DC ON flashcard_modification (deck_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_kanji DROP kanji');
        $this->addSql('ALTER TABLE flashcard_modification DROP FOREIGN KEY FK_9BFCCF72111948DC');
        $this->addSql('DROP INDEX IDX_9BFCCF72111948DC ON flashcard_modification');
        $this->addSql('ALTER TABLE flashcard_modification DROP deck_id, CHANGE user_id user_id INT NOT NULL, CHANGE flashcard_id flashcard_id INT NOT NULL');
    }
}
