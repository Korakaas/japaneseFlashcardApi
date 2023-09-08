<?php

namespace App\Entity;

use App\Repository\FlashcardRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlashcardRepository::class)]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\Column]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\NotBlank(message: "La traduction est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le traduction ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $translation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ furigana ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $furigana = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'exemple' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $example = null;

    #[ORM\Column(nullable: true)]
    private ?bool $duplicate = false;

    #[ORM\ManyToMany(targetEntity: Deck::class, inversedBy: 'flashcards')]
    private Collection $decks;

    #[ORM\OneToMany(mappedBy: 'flashcard', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'flashcard', targetEntity: FlashcardModification::class, orphanRemoval: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    private Collection $flashcardModifications;

    #[ORM\Column(nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'flashcards')]
    private Collection $user;

    public function __construct()
    {
        $this->decks = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->flashcardModifications = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function toArray(): array
    {
        $data =  [
            'id' => $this->id,
            'translation' => $this->translation,
            'furigana' => $this->furigana,
            'example' => $this->example,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];

        if ($this instanceof FlashcardKanji) {
            $data['onyomi'] = $this->getOnyomi();
            $data['kunyomi'] = $this->getKunyomi();
            $data['kanji'] = $this->getKanji();
        }
        if ($this instanceof FlashcardGrammar) {
            $data['grammarRule'] = $this->getGrammarRule();
            $data['grammarPoint'] = $this->getGrammarPoint();
        }
        if ($this instanceof FlashcardVocabulary) {
            $data['word'] = $this->getWord();
            $data['image'] = $this->getImage();
            $data['audio'] = $this->getAudio();
        }
        if ($this instanceof FlashcardConjugation) {
            $data['polite'] = $this->getPolite();
            $data['negative'] = $this->getNegative();
            $data['conditionnalBa'] = $this->getConditionnalBa();
            $data['conditionalTara'] = $this->getConditionalTara();
            $data['imperative'] = $this->getImperative();
            $data['volitionnal'] = $this->getVolitionnal();
            $data['causative'] = $this->getCausative();
            $data['potential'] = $this->getPotential();
            $data['taForm'] = $this->getTaForm();
            $data['teForm'] = $this->getTeForm();

        }

        return $data;

    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }
}
