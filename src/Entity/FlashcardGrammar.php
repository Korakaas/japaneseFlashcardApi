<?php

namespace App\Entity;

use App\Repository\FlashcardGrammarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlashcardGrammarRepository::class)]
class FlashcardGrammar extends Flashcard
{
    #[ORM\Column(length: 255)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'point de grammaire' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $grammarPoint = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'règle de grammaire' ne peut pas faire plus de {{ limit }} caractères",
    )]
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
