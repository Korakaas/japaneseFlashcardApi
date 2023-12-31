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

    /**La carte à qui apprtient les données de révision */
    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Flashcard $flashcard = null;

    /**L'utilisateur qui a révisé la carte */
    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $reviewedAt = null;

    /**Niveau de connaissance de la carte */
    #[ORM\Column]
    private ?int $knownLevel = null;

    /**facteur de facilité */
    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $easeFactor = null;

    /**Interval entre chaque révision */
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $intervalReview = null;

    /**Score de révision compris entre 1 et 5 */
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
        $this->knownLevel = $knownLevel;

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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'flashcardId' => $this->flashcard->getId(),
            'userId' => $this->user->getId(),
            'reviewed_at' => $this->reviewedAt,
            'knownLevel' => $this->knownLevel,
            'easeFactor' => $this->easeFactor,
            'intervalReview' => $this->intervalReview,
            'score' => $this->score,
        ];
    }
}
