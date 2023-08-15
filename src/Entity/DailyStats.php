<?php

namespace App\Entity;

use App\Repository\DailyStatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DailyStatsRepository::class)]
class DailyStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // #[Groups(["getDecks"])]
    private ?int $id = null;

    #[ORM\Column]
    // #[Groups(["getDecks"])]
    private ?\DateTimeImmutable $date = null;

    // #[Groups(["getDecks"])]
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $dailyReviewNumber = null;

    // #[Groups(["getDecks"])]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $correctAnswerNumber = null;

    #[ORM\ManyToOne(inversedBy: 'dailyStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Deck $deck = null;

    #[ORM\ManyToOne(inversedBy: 'dailyStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDailyReviewNumber(): ?int
    {
        return $this->dailyReviewNumber;
    }

    public function setDailyReviewNumber(?int $dailyReviewNumber): static
    {
        $this->dailyReviewNumber = $dailyReviewNumber;

        return $this;
    }

    public function getCorrectAnswerNumber(): ?int
    {
        return $this->correctAnswerNumber;
    }

    public function setCorrectAnswerNumber(int $correctAnswerNumber): static
    {
        $this->correctAnswerNumber = $correctAnswerNumber;

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }
}
