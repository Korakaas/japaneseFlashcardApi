<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815104902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deck_user (deck_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B1749277111948DC (deck_id), INDEX IDX_B1749277A76ED395 (user_id), PRIMARY KEY(deck_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE deck_user ADD CONSTRAINT FK_B1749277111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deck_user ADD CONSTRAINT FK_B1749277A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deck_user DROP FOREIGN KEY FK_B1749277111948DC');
        $this->addSql('ALTER TABLE deck_user DROP FOREIGN KEY FK_B1749277A76ED395');
        $this->addSql('DROP TABLE deck_user');
    }
}
