<?php

namespace App\Entity;

use App\Repository\DeckRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DeckRepository::class)]
class Deck
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["getDecks"])]
    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[Groups(["getDecks"])]
    #[ORM\Column]
    private ?bool $public = null;

    #[Groups(["getDecks"])]
    #[ORM\Column]
    private ?bool $reverse = null;

    #[Groups(["getDecks"])]
    #[ORM\OneToMany(mappedBy: 'deck', targetEntity: Flashcard::class, orphanRemoval: true)]
    private Collection $flashcards;

    // #[Groups(["getDecks"])]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'decks')]
    private Collection $user;

    // #[Groups(["getDecks"])]
    #[ORM\OneToMany(mappedBy: 'deck', targetEntity: DailyStats::class, orphanRemoval: true)]
    private Collection $dailyStats;

    public function __construct()
    {
        $this->flashcards = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->dailyStats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {=
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

    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    public function isReverse(): ?bool
    {
        return $this->reverse;
    }

    public function setReverse(bool $reverse): static
    {
        $this->reverse = $reverse;

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
            $flashcard->setDeck($this);
        }

        return $this;
    }

    public function removeFlashcard(Flashcard $flashcard): static
    {
        if ($this->flashcards->removeElement($flashcard)) {
            // set the owning side to null (unless already changed)
            if ($flashcard->getDeck() === $this) {
                $flashcard->setDeck(null);
            }
        }

        return $this;
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
}
