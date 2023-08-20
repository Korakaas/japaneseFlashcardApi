<?php

namespace App\Entity;

use App\Repository\FlashcardModificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlashcardModificationRepository::class)]
class FlashcardModification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'flashcardModifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'flashcardModifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Flashcard $flashcard = null;

    #[ORM\Column]
    private array $modifications = [];

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFlashcard(): ?Flashcard
    {
        return $this->flashcard;
    }

    public function setFlashcard(?Flashcard $flashcard): static
    {
        $this->flashcard = $flashcard;

        return $this;
    }

    public function getModifications(): array
    {
        return $this->modifications;
    }

    public function setModifications(array $modifications): static
    {
        $this->modifications = $modifications;

        return $this;
    }
}
