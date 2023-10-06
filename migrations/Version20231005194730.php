<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005194730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_conjugation DROP FOREIGN KEY FK_9A28A02EBF396750');
        $this->addSql('DROP TABLE flashcard_conjugation');
        $this->addSql('ALTER TABLE flashcard_modification DROP image, DROP audio');
        $this->addSql('ALTER TABLE flashcard_vocabulary DROP image, DROP audio');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flashcard_conjugation (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE flashcard_conjugation ADD CONSTRAINT FK_9A28A02EBF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_modification ADD image VARCHAR(255) DEFAULT NULL, ADD audio VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE flashcard_vocabulary ADD image VARCHAR(255) DEFAULT NULL, ADD audio VARCHAR(255) DEFAULT NULL');
    }
}
