<?php

namespace App\Entity;

use App\Repository\DailyStatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DailyStatsRepository::class)]
class DailyStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dailyStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Deck $deck = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $flashcardsReviewed = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $correctAnswers = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getFlashcardsReviewed(): ?int
    {
        return $this->flashcardsReviewed;
    }

    public function setFlashcardsReviewed(int $flashcardsReviewed): static
    {
        $this->flashcardsReviewed = $flashcardsReviewed;

        return $this;
    }

    public function getCorrectAnswers(): ?int
    {
        return $this->correctAnswers;
    }

    public function setCorrectAnswers(int $correctAnswers): static
    {
        $this->correctAnswers = $correctAnswers;

        return $this;
    }
}
