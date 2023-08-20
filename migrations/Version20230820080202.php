<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230820080202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flascard_modification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, flashcard_id INT NOT NULL, modifications JSON NOT NULL, INDEX IDX_81144CB6A76ED395 (user_id), INDEX IDX_81144CB6C5D16576 (flashcard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flashcard_deck (flashcard_id INT NOT NULL, deck_id INT NOT NULL, INDEX IDX_627CAED7C5D16576 (flashcard_id), INDEX IDX_627CAED7111948DC (deck_id), PRIMARY KEY(flashcard_id, deck_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, flashcard_id INT NOT NULL, user_id INT NOT NULL, reviewed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', review_number INT NOT NULL, ease_factor NUMERIC(3, 1) NOT NULL, interval_review NUMERIC(5, 2) NOT NULL, score SMALLINT NOT NULL, INDEX IDX_794381C6C5D16576 (flashcard_id), INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flascard_modification ADD CONSTRAINT FK_81144CB6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE flascard_modification ADD CONSTRAINT FK_81144CB6C5D16576 FOREIGN KEY (flashcard_id) REFERENCES flashcard (id)');
        $this->addSql('ALTER TABLE flashcard_deck ADD CONSTRAINT FK_627CAED7C5D16576 FOREIGN KEY (flashcard_id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_deck ADD CONSTRAINT FK_627CAED7111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6C5D16576 FOREIGN KEY (flashcard_id) REFERENCES flashcard (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE deck_user DROP FOREIGN KEY FK_B1749277111948DC');
        $this->addSql('ALTER TABLE deck_user DROP FOREIGN KEY FK_B1749277A76ED395');
        $this->addSql('ALTER TABLE flashcard_conjugation DROP FOREIGN KEY FK_9A28A02EBF396750');
        $this->addSql('ALTER TABLE flashcard_grammar DROP FOREIGN KEY FK_ECC26032BF396750');
        $this->addSql('ALTER TABLE flashcard_kanji DROP FOREIGN KEY FK_E248AF3CBF396750');
        $this->addSql('ALTER TABLE flashcard_vocabulary DROP FOREIGN KEY FK_716AD202BF396750');
        $this->addSql('DROP TABLE deck_user');
        $this->addSql('DROP TABLE flashcard_conjugation');
        $this->addSql('DROP TABLE flashcard_grammar');
        $this->addSql('DROP TABLE flashcard_kanji');
        $this->addSql('DROP TABLE flashcard_vocabulary');
        $this->addSql('ALTER TABLE daily_stats DROP FOREIGN KEY FK_D766067EA76ED395');
        $this->addSql('DROP INDEX IDX_D766067EA76ED395 ON daily_stats');
        $this->addSql('ALTER TABLE daily_stats ADD correct_answers SMALLINT NOT NULL, DROP user_id, DROP daily_review_number, CHANGE correct_answer_number flashcards_reviewed SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE deck ADD user_id INT NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE public public TINYINT(1) DEFAULT NULL, CHANGE reverse reverse TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE deck ADD CONSTRAINT FK_4FAC3637A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4FAC3637A76ED395 ON deck (user_id)');
        $this->addSql('ALTER TABLE flashcard DROP FOREIGN KEY FK_70511A09111948DC');
        $this->addSql('DROP INDEX IDX_70511A09111948DC ON flashcard');
        $this->addSql('ALTER TABLE flashcard ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD duplicate TINYINT(1) DEFAULT NULL, DROP deck_id, DROP reviewed_at, DROP review_number, DROP review_interval, DROP score, DROP type, CHANGE example example VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deck_user (deck_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B1749277111948DC (deck_id), INDEX IDX_B1749277A76ED395 (user_id), PRIMARY KEY(deck_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE flashcard_conjugation (id INT NOT NULL, polite VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, negative VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, conditionnal_ba VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, conditional_tara VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, imperative VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, volitionnal VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, causative VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, potential VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, te_form VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ta_form VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE flashcard_grammar (id INT NOT NULL, grammar_point VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, grammar_rule VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE flashcard_kanji (id INT NOT NULL, onyomi VARCHAR(60) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, kunyomi VARCHAR(60) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE flashcard_vocabulary (id INT NOT NULL, word VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, audio VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE deck_user ADD CONSTRAINT FK_B1749277111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deck_user ADD CONSTRAINT FK_B1749277A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_conjugation ADD CONSTRAINT FK_9A28A02EBF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_grammar ADD CONSTRAINT FK_ECC26032BF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_kanji ADD CONSTRAINT FK_E248AF3CBF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flashcard_vocabulary ADD CONSTRAINT FK_716AD202BF396750 FOREIGN KEY (id) REFERENCES flashcard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flascard_modification DROP FOREIGN KEY FK_81144CB6A76ED395');
        $this->addSql('ALTER TABLE flascard_modification DROP FOREIGN KEY FK_81144CB6C5D16576');
        $this->addSql('ALTER TABLE flashcard_deck DROP FOREIGN KEY FK_627CAED7C5D16576');
        $this->addSql('ALTER TABLE flashcard_deck DROP FOREIGN KEY FK_627CAED7111948DC');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6C5D16576');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('DROP TABLE flascard_modification');
        $this->addSql('DROP TABLE flashcard_deck');
        $this->addSql('DROP TABLE review');
        $this->addSql('ALTER TABLE daily_stats ADD user_id INT NOT NULL, ADD daily_review_number SMALLINT DEFAULT NULL, ADD correct_answer_number SMALLINT NOT NULL, DROP flashcards_reviewed, DROP correct_answers');
        $this->addSql('ALTER TABLE daily_stats ADD CONSTRAINT FK_D766067EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D766067EA76ED395 ON daily_stats (user_id)');
        $this->addSql('ALTER TABLE deck DROP FOREIGN KEY FK_4FAC3637A76ED395');
        $this->addSql('DROP INDEX IDX_4FAC3637A76ED395 ON deck');
        $this->addSql('ALTER TABLE deck DROP user_id, DROP description, DROP created_at, CHANGE public public TINYINT(1) NOT NULL, CHANGE reverse reverse TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE flashcard ADD deck_id INT NOT NULL, ADD reviewed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD review_number INT DEFAULT NULL, ADD review_interval NUMERIC(5, 2) DEFAULT NULL, ADD score SMALLINT DEFAULT NULL, ADD type VARCHAR(255) NOT NULL, DROP created_at, DROP duplicate, CHANGE example example VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE flashcard ADD CONSTRAINT FK_70511A09111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('CREATE INDEX IDX_70511A09111948DC ON flashcard (deck_id)');
    }
}
