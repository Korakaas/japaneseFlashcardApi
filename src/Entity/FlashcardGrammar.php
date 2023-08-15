<?php

namespace App\Entity;

use App\Repository\FlashcardGrammarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlashcardGrammarRepository::class)]
class FlashcardGrammar extends Flashcard
{

    #[ORM\Column(length: 255)]
    private ?string $grammarPoint = null;

    #[ORM\Column(length: 255)]
    private ?string $grammarRule = null;

    public function getGrammarPoint(): ?string
    {
        return $this->grammarPoint;
    }

    public function setGrammarPoint(string $grammarPoint): static
    {
        $this->grammarPoint = $grammarPoint;

        return $this;
    }

    public function getGrammarRule(): ?string
    {
        return $this->grammarRule;
    }

    public function setGrammarRule(string $grammarRule): static
    {
        $this->grammarRule = $grammarRule;

        return $this;
    }
}
