<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230820094619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flashcard_modification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, flashcard_id INT NOT NULL, modifications JSON NOT NULL, INDEX IDX_9BFCCF72A76ED395 (user_id), INDEX IDX_9BFCCF72C5D16576 (flashcard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flashcard_modification ADD CONSTRAINT FK_9BFCCF72A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE flashcard_modification ADD CONSTRAINT FK_9BFCCF72C5D16576 FOREIGN KEY (flashcard_id) REFERENCES flashcard (id)');
        $this->addSql('ALTER TABLE flascard_modification DROP FOREIGN KEY FK_81144CB6A76ED395');
        $this->addSql('ALTER TABLE flascard_modification DROP FOREIGN KEY FK_81144CB6C5D16576');
        $this->addSql('DROP TABLE flascard_modification');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flascard_modification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, flashcard_id INT NOT NULL, modifications JSON NOT NULL, INDEX IDX_81144CB6A76ED395 (user_id), INDEX IDX_81144CB6C5D16576 (flashcard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE flascard_modification ADD CONSTRAINT FK_81144CB6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE flascard_modification ADD CONSTRAINT FK_81144CB6C5D16576 FOREIGN KEY (flashcard_id) REFERENCES flashcard (id)');
        $this->addSql('ALTER TABLE flashcard_modification DROP FOREIGN KEY FK_9BFCCF72A76ED395');
        $this->addSql('ALTER TABLE flashcard_modification DROP FOREIGN KEY FK_9BFCCF72C5D16576');
        $this->addSql('DROP TABLE flashcard_modification');
    }
}
