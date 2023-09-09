<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Flashcard $flashcard = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $reviewedAt = null;

    #[ORM\Column]
    private ?int $knownLevel= null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $easeFactor = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $intervalReview = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $score = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlashcard(): ?Flashcard
    {
        return $this->flashcard;
    }

    public function setFlashcard(?Flashcard $flashcard): static
    {
        $this->flashcard = $flashcard;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getReviewedAt(): ?\DateTimeImmutable
    {
        return $this->reviewedAt;
    }

    public function setReviewedAt(\DateTimeImmutable $reviewedAt): static
    {
        $this->reviewedAt = $reviewedAt;

        return $this;
    }

    public function getknownLevel(): ?int
    {
        return $this->knownLevel;
    }

    public function setknownLevel(int $knownLevel): static
    {
        $this->knownLevel= $knownLevel;

        return $this;
    }

    public function getEaseFactor(): ?string
    {
        return $this->easeFactor;
    }

    public function setEaseFactor(string $easeFactor): static
    {
        $this->easeFactor = $easeFactor;

        return $this;
    }

    public function getIntervalReview(): ?string
    {
        return $this->intervalReview;
    }

    public function setIntervalReview(string $intervalReview): static
    {
        $this->intervalReview = $intervalReview;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }
}
