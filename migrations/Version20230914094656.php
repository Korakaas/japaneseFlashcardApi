<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230914094656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard ADD back VARCHAR(255) NOT NULL, CHANGE translation front VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE flashcard_conjugation DROP polite, DROP negative, DROP conditionnal_ba, DROP conditionnal_tara, DROP imperative, DROP volitional, DROP causative, DROP potential, DROP te_form, DROP ta_form, DROP dictionnary');
        $this->addSql('ALTER TABLE flashcard_grammar ADD construction VARCHAR(255) NOT NULL, ADD grammarnotes VARCHAR(255) DEFAULT NULL, DROP grammar_point, DROP grammar_rule');
        $this->addSql('ALTER TABLE flashcard_kanji ADD mnemonic VARCHAR(255) DEFAULT NULL, DROP kanji');
        $this->addSql('ALTER TABLE flashcard_modification ADD front VARCHAR(255) DEFAULT NULL, ADD back VARCHAR(255) DEFAULT NULL, ADD mnemotic VARCHAR(255) DEFAULT NULL, ADD construction VARCHAR(255) DEFAULT NULL, ADD grammarnotes VARCHAR(255) DEFAULT NULL, DROP translation, DROP polite, DROP negative, DROP conditionnal_ba, DROP conditionnal_tara, DROP imperative, DROP volitional, DROP causative, DROP potential, DROP te_form, DROP ta_form, DROP kanji, DROP grammar_point, DROP grammar_rule, DROP word');
        $this->addSql('ALTER TABLE flashcard_vocabulary DROP word');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard ADD translation VARCHAR(255) NOT NULL, DROP front, DROP back');
        $this->addSql('ALTER TABLE flashcard_conjugation ADD polite VARCHAR(30) DEFAULT NULL, ADD negative VARCHAR(30) DEFAULT NULL, ADD conditionnal_ba VARCHAR(30) DEFAULT NULL, ADD conditionnal_tara VARCHAR(30) DEFAULT NULL, ADD imperative VARCHAR(30) DEFAULT NULL, ADD volitional VARCHAR(30) DEFAULT NULL, ADD causative VARCHAR(30) DEFAULT NULL, ADD potential VARCHAR(30) DEFAULT NULL, ADD te_form VARCHAR(30) DEFAULT NULL, ADD ta_form VARCHAR(30) DEFAULT NULL, ADD dictionnary VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE flashcard_grammar ADD grammar_rule VARCHAR(255) NOT NULL, DROP grammarnotes, CHANGE construction grammar_point VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE flashcard_kanji ADD kanji VARCHAR(10) NOT NULL, DROP mnemonic');
        $this->addSql('ALTER TABLE flashcard_modification ADD translation VARCHAR(255) DEFAULT NULL, ADD polite VARCHAR(30) DEFAULT NULL, ADD negative VARCHAR(30) DEFAULT NULL, ADD conditionnal_ba VARCHAR(30) DEFAULT NULL, ADD conditionnal_tara VARCHAR(30) DEFAULT NULL, ADD imperative VARCHAR(30) DEFAULT NULL, ADD volitional VARCHAR(30) DEFAULT NULL, ADD causative VARCHAR(30) DEFAULT NULL, ADD potential VARCHAR(30) DEFAULT NULL, ADD te_form VARCHAR(30) DEFAULT NULL, ADD ta_form VARCHAR(30) DEFAULT NULL, ADD kanji VARCHAR(10) DEFAULT NULL, ADD grammar_point VARCHAR(255) DEFAULT NULL, ADD grammar_rule VARCHAR(255) DEFAULT NULL, ADD word VARCHAR(10) DEFAULT NULL, DROP front, DROP back, DROP mnemotic, DROP construction, DROP grammarnotes');
        $this->addSql('ALTER TABLE flashcard_vocabulary ADD word VARCHAR(10) NOT NULL');
    }
}
