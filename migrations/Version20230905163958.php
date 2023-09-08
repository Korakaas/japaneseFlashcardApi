<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230905163958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flashcard_user (flashcard_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A0434EA9C5D16576 (flashcard_id), INDEX IDX_A0434EA9A76ED395 (user_id), PRIMARY KEY(flashcard_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flashcard_user ADD CONSTRAINT FK_A0434EA9C5D16576 FOREIGN KEY (flashcard_id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_user ADD CONSTRAINT FK_A0434EA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_user DROP FOREIGN KEY FK_A0434EA9C5D16576');
        $this->addSql('ALTER TABLE flashcard_user DROP FOREIGN KEY FK_A0434EA9A76ED395');
        $this->addSql('DROP TABLE flashcard_user');
    }
}
