<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005184030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard DROP FOREIGN KEY FK_70511A09C5D16576');
        $this->addSql('DROP INDEX UNIQ_70511A09C5D16576 ON flashcard');
        $this->addSql('ALTER TABLE flashcard DROP flashcard_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard ADD flashcard_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE flashcard ADD CONSTRAINT FK_70511A09C5D16576 FOREIGN KEY (flashcard_id) REFERENCES flashcard (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70511A09C5D16576 ON flashcard (flashcard_id)');
    }
}
