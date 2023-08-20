<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230820084643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flashcard_conjugation (id INT NOT NULL, polite VARCHAR(30) NOT NULL, negative VARCHAR(30) NOT NULL, conditionnal_ba VARCHAR(30) NOT NULL, conditional_tara VARCHAR(30) NOT NULL, imperative VARCHAR(30) NOT NULL, volitionnal VARCHAR(30) NOT NULL, causative VARCHAR(30) NOT NULL, potential VARCHAR(30) NOT NULL, te_form VARCHAR(30) NOT NULL, ta_form VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flashcard_grammar (id INT NOT NULL, grammar_point VARCHAR(255) NOT NULL, grammar_rule VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flashcard_kanji (id INT NOT NULL, onyomi VARCHAR(60) DEFAULT NULL, kunyomi VARCHAR(60) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flashcard_vocabulary (id INT NOT NULL, word VARCHAR(10) NOT NULL, image VARCHAR(255) DEFAULT NULL, audio VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flashcard_conjugation ADD CONSTRAINT FK_9A28A02EBF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_grammar ADD CONSTRAINT FK_ECC26032BF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_kanji ADD CONSTRAINT FK_E248AF3CBF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_vocabulary ADD CONSTRAINT FK_716AD202BF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deck ADD user_id INT NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE public public TINYINT(1) DEFAULT NULL, CHANGE reverse reverse TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE deck ADD CONSTRAINT FK_4FAC3637A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4FAC3637A76ED395 ON deck (user_id)');
        $this->addSql('ALTER TABLE flashcard DROP FOREIGN KEY FK_70511A09111948DC');
        $this->addSql('DROP INDEX IDX_70511A09111948DC ON flashcard');
        $this->addSql('ALTER TABLE flashcard ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD duplicate TINYINT(1) DEFAULT NULL, DROP deck_id, DROP reviewed_at, DROP review_number, DROP review_interval, DROP score, CHANGE example example VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_conjugation DROP FOREIGN KEY FK_9A28A02EBF396750');
        $this->addSql('ALTER TABLE flashcard_grammar DROP FOREIGN KEY FK_ECC26032BF396750');
        $this->addSql('ALTER TABLE flashcard_kanji DROP FOREIGN KEY FK_E248AF3CBF396750');
        $this->addSql('ALTER TABLE flashcard_vocabulary DROP FOREIGN KEY FK_716AD202BF396750');
        $this->addSql('DROP TABLE flashcard_conjugation');
        $this->addSql('DROP TABLE flashcard_grammar');
        $this->addSql('DROP TABLE flashcard_kanji');
        $this->addSql('DROP TABLE flashcard_vocabulary');
        $this->addSql('ALTER TABLE deck DROP FOREIGN KEY FK_4FAC3637A76ED395');
        $this->addSql('DROP INDEX IDX_4FAC3637A76ED395 ON deck');
        $this->addSql('ALTER TABLE deck DROP user_id, DROP description, DROP created_at, CHANGE public public TINYINT(1) NOT NULL, CHANGE reverse reverse TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE flashcard ADD deck_id INT NOT NULL, ADD reviewed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD review_number INT DEFAULT NULL, ADD review_interval NUMERIC(5, 2) DEFAULT NULL, ADD score SMALLINT DEFAULT NULL, DROP created_at, DROP duplicate, CHANGE example example VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE flashcard ADD CONSTRAINT FK_70511A09111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('CREATE INDEX IDX_70511A09111948DC ON flashcard (deck_id)');
    }
}
