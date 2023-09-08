<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904172618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_modification ADD translation VARCHAR(255) DEFAULT NULL, ADD furigana VARCHAR(255) DEFAULT NULL, ADD example VARCHAR(255) DEFAULT NULL, ADD polite VARCHAR(30) DEFAULT NULL, ADD negative VARCHAR(30) DEFAULT NULL, ADD conditionnal_ba VARCHAR(30) DEFAULT NULL, ADD conditionnal_tara VARCHAR(30) DEFAULT NULL, ADD imperative VARCHAR(30) DEFAULT NULL, ADD volitional VARCHAR(30) DEFAULT NULL, ADD causative VARCHAR(30) DEFAULT NULL, ADD potential VARCHAR(30) DEFAULT NULL, ADD te_form VARCHAR(30) DEFAULT NULL, ADD ta_form VARCHAR(30) DEFAULT NULL, ADD onyomi VARCHAR(60) DEFAULT NULL, ADD kunyomi VARCHAR(60) DEFAULT NULL, ADD kanji VARCHAR(10) DEFAULT NULL, ADD grammar_point VARCHAR(255) DEFAULT NULL, ADD grammar_rule VARCHAR(255) DEFAULT NULL, ADD word VARCHAR(10) DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD audio VARCHAR(255) DEFAULT NULL, DROP modifications');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_modification ADD modifications JSON NOT NULL, DROP translation, DROP furigana, DROP example, DROP polite, DROP negative, DROP conditionnal_ba, DROP conditionnal_tara, DROP imperative, DROP volitional, DROP causative, DROP potential, DROP te_form, DROP ta_form, DROP onyomi, DROP kunyomi, DROP kanji, DROP grammar_point, DROP grammar_rule, DROP word, DROP image, DROP audio');
    }
}
