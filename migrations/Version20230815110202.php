<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815110202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE daily_stats (id INT AUTO_INCREMENT NOT NULL, deck_id INT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', daily_review_number SMALLINT DEFAULT NULL, correct_answer_number SMALLINT NOT NULL, INDEX IDX_D766067E111948DC (deck_id), INDEX IDX_D766067EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE daily_stats ADD CONSTRAINT FK_D766067E111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('ALTER TABLE daily_stats ADD CONSTRAINT FK_D766067EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE daily_stats DROP FOREIGN KEY FK_D766067E111948DC');
        $this->addSql('ALTER TABLE daily_stats DROP FOREIGN KEY FK_D766067EA76ED395');
        $this->addSql('DROP TABLE daily_stats');
    }
}
