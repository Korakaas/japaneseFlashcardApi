<?php

namespace App\Entity;

use App\Repository\DeckRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeckRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Deck
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getDetailDeck"])]
    private ?int $id = null;

    /** L'utilisateur à qui appartient le paquet */
    #[ORM\ManyToOne(inversedBy: 'decks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Utilisateur obligatoire")]
    private ?User $user = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank(message: "Le titre du paquet est obligatoire")]
    #[Assert\Length(
        max: 40,
        maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères",
    )]
    #[Groups(["getDetailDeck", 'deck_update'])]
    private ?string $name = null;

    /**Si le deck sera visible par les autres utilisateurs ou non */
    #[ORM\Column(nullable: true)]
    #[Groups(["getDetailDeck", 'deck_update'])]
    private ?bool $public = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["getDetailDeck", 'deck_update'])]
    #[Assert\Length(
        max: 1024,
        maxMessage: "La description ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(["getDetailDeck"])]
    private ?\DateTimeImmutable $createdAt = null;

    /** Les cartes appartenant au paquet */
    #[ORM\ManyToMany(targetEntity: Flashcard::class, mappedBy: 'decks')]
    #[Groups(["getDetailDeck"])]
    private Collection $flashcards;

    /** Les stats su paquet */
    #[ORM\OneToMany(mappedBy: 'deck', targetEntity: DailyStats::class, orphanRemoval: true)]
    private Collection $dailyStats;

    #[ORM\Column(nullable: true)]
    #[Groups(["getDetailDeck"])]
    private ?\DateTimeImmutable $updatedAt = null;

    /** Les modifications des cartes spécifiques au paquet */
    #[ORM\OneToMany(mappedBy: 'deck', targetEntity: FlashcardModification::class, orphanRemoval: true)]
    private Collection $flashcardModifications;

    public function __construct()
    {
        $this->flashcards = new ArrayCollection();
        $this->dailyStats = new ArrayCollection();
        $this->flashcardModifications = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(?bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new DateTimeImmutable('now');

        return $this;
    }

    /**
     * @return Collection<int, Flashcard>
     */
    public function getFlashcards(): Collection
    {
        return $this->flashcards;
    }

    public function addFlashcard(Flashcard $flashcard): static
    {
        if (!$this->flashcards->contains($flashcard)) {
            $this->flashcards->add($flashcard);
            $flashcard->addDeck($this);
        }

        return $this;
    }

    public function removeFlashcard(Flashcard $flashcard): static
    {
        if ($this->flashcards->removeElement($flashcard)) {
            $flashcard->removeDeck($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DailyStats>
     */
    public function getDailyStats(): Collection
    {
        return $this->dailyStats;
    }

    public function addDailyStat(DailyStats $dailyStat): static
    {
        if (!$this->dailyStats->contains($dailyStat)) {
            $this->dailyStats->add($dailyStat);
            $dailyStat->setDeck($this);
        }

        return $this;
    }

    public function removeDailyStat(DailyStats $dailyStat): static
    {
        if ($this->dailyStats->removeElement($dailyStat)) {
            // set the owning side to null (unless already changed)
            if ($dailyStat->getDeck() === $this) {
                $dailyStat->setDeck(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTimeImmutable('now');

        return $this;
    }

    public function toArray(): array
    {
        return  [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'public' => $this->public,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'user' => $this->user->toArray()
        ];
    }

    /**
     * @return Collection<int, FlashcardModification>
     */
    public function getFlashcardModifications(): Collection
    {
        return $this->flashcardModifications;
    }

    public function addFlashcardModification(FlashcardModification $flashcardModification): static
    {
        if (!$this->flashcardModifications->contains($flashcardModification)) {
            $this->flashcardModifications->add($flashcardModification);
            $flashcardModification->setDeck($this);
        }

        return $this;
    }

    public function removeFlashcardModification(FlashcardModification $flashcardModification): static
    {
        if ($this->flashcardModifications->removeElement($flashcardModification)) {
            // set the owning side to null (unless already changed)
            if ($flashcardModification->getDeck() === $this) {
                $flashcardModification->setDeck(null);
            }
        }

        return $this;
    }
}
