<?php

namespace App\Entity;

use App\Repository\FlashcardRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
#[ORM\HasLifecycleCallbacks]
class Flashcard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["getDetailDeck"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDetailDeck"])]
    private ?string $translation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDetailDeck"])]
    private ?string $furigana = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDetailDeck"])]
    private ?string $example = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getDetailDeck"])]
    private ?bool $duplicate = false;

    #[ORM\ManyToMany(targetEntity: Deck::class, inversedBy: 'flashcards')]
    private Collection $decks;

    #[ORM\OneToMany(mappedBy: 'flashcard', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'flashcard', targetEntity: FlashcardModification::class, orphanRemoval: true)]
    #[Groups(["getDetailDeck"])]
    private Collection $flashcardModifications;

    #[ORM\Column(nullable: true)]
    #[Groups(["getDetailDeck"])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->decks = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->flashcardModifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\PrePersist]
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): self
    {
        $this->createdAt = new DateTimeImmutable('now');

        return $this;
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

    public function setExample(string $example): static
    {
        $this->example = $example;

        return $this;
    }

    public function isDuplicate(): ?bool
    {
        return $this->duplicate;
    }

    public function setDuplicate(?bool $duplicate): static
    {
        $this->duplicate = $duplicate;

        return $this;
    }

    /**
     * @return Collection<int, Deck>
     */
    public function getDecks(): Collection
    {
        return $this->decks;
    }

    public function addDeck(Deck $deck): static
    {
        if (!$this->decks->contains($deck)) {
            $this->decks->add($deck);
        }

        return $this;
    }

    public function removeDeck(Deck $deck): static
    {
        $this->decks->removeElement($deck);

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setFlashcard($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getFlashcard() === $this) {
                $review->setFlashcard(null);
            }
        }

        return $this;
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
            $flashcardModification->setFlashcard($this);
        }

        return $this;
    }

    public function removeFlashcardModification(FlashcardModification $flashcardModification): static
    {
        if ($this->flashcardModifications->removeElement($flashcardModification)) {
            // set the owning side to null (unless already changed)
            if ($flashcardModification->getFlashcard() === $this) {
                $flashcardModification->setFlashcard(null);
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
}