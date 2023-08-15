<?php

namespace App\Entity;

use App\Repository\FlashcardRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlashcardRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'flashcard' => Flashcard::class,
    'kanji' => FlashcardKanji::class,
    'grammar' => FlashcardGrammar::class,
    'vocabulary' => FlashcardVocabulary::class,
    'conjugation' => FlashcardConjugation::class
])]

class Flashcard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $translation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $furigana = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $example = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $reviewedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $reviewNumber = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $reviewInterval = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $score = null;

    #[ORM\ManyToOne(inversedBy: 'flashcards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Deck $deck = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranslation(): ?string
    {
        return $this->translation;
    }

    public function setTranslation(string $translation): static
    {
        $this->translation = $translation;

        return $this;
    }

    public function getFurigana(): ?string
    {
        return $this->furigana;
    }

    public function setFurigana(?string $furigana): static
    {
        $this->furigana = $furigana;

        return $this;
    }

    public function getExample(): ?string
    {
        return $this->example;
    }

    public function setExample(?string $example): static
    {
        $this->example = $example;

        return $this;
    }

    public function getReviewedAt(): ?\DateTimeImmutable
    {
        return $this->reviewedAt;
    }

    public function setReviewedAt(?\DateTimeImmutable $reviewedAt): static
    {
        $this->reviewedAt = $reviewedAt;

        return $this;
    }

    public function getReviewNumber(): ?int
    {
        return $this->reviewNumber;
    }

    public function setReviewNumber(?int $reviewNumber): static
    {
        $this->reviewNumber = $reviewNumber;

        return $this;
    }

    public function getReviewInterval(): ?string
    {
        return $this->reviewInterval;
    }

    public function setReviewInterval(?string $reviewInterval): static
    {
        $this->reviewInterval = $reviewInterval;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    public function setDeck(?Deck $deck): static
    {
        $this->deck = $deck;

        return $this;
    }
}
