<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815104738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flashcard (id INT AUTO_INCREMENT NOT NULL, deck_id INT NOT NULL, translation VARCHAR(255) NOT NULL, furigana VARCHAR(255) DEFAULT NULL, example VARCHAR(255) DEFAULT NULL, reviewed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', review_number INT DEFAULT NULL, review_interval NUMERIC(5, 2) DEFAULT NULL, score SMALLINT DEFAULT NULL, INDEX IDX_70511A09111948DC (deck_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flashcard ADD CONSTRAINT FK_70511A09111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard DROP FOREIGN KEY FK_70511A09111948DC');
        $this->addSql('DROP TABLE flashcard');
    }
}
