<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910150410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_conjugation CHANGE polite polite VARCHAR(30) DEFAULT NULL, CHANGE negative negative VARCHAR(30) DEFAULT NULL, CHANGE conditionnal_ba conditionnal_ba VARCHAR(30) DEFAULT NULL, CHANGE conditional_tara conditional_tara VARCHAR(30) DEFAULT NULL, CHANGE imperative imperative VARCHAR(30) DEFAULT NULL, CHANGE volitionnal volitionnal VARCHAR(30) DEFAULT NULL, CHANGE causative causative VARCHAR(30) DEFAULT NULL, CHANGE potential potential VARCHAR(30) DEFAULT NULL, CHANGE te_form te_form VARCHAR(30) DEFAULT NULL, CHANGE ta_form ta_form VARCHAR(30) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flashcard_conjugation CHANGE polite polite VARCHAR(30) NOT NULL, CHANGE negative negative VARCHAR(30) NOT NULL, CHANGE conditionnal_ba conditionnal_ba VARCHAR(30) NOT NULL, CHANGE conditional_tara conditional_tara VARCHAR(30) NOT NULL, CHANGE imperative imperative VARCHAR(30) NOT NULL, CHANGE volitionnal volitionnal VARCHAR(30) NOT NULL, CHANGE causative causative VARCHAR(30) NOT NULL, CHANGE potential potential VARCHAR(30) NOT NULL, CHANGE te_form te_form VARCHAR(30) NOT NULL, CHANGE ta_form ta_form VARCHAR(30) NOT NULL');
    }
}
