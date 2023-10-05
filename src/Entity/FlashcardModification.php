<?php

namespace App\Entity;

use App\Repository\FlashcardModificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlashcardModificationRepository::class)]
class FlashcardModification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** L'utilisateur à qui apprtient les modif */
    #[ORM\ManyToOne(inversedBy: 'flashcardModifications')]
    private ?User $user = null;

    /** la carte à qui appartient les modif */
    #[ORM\ManyToOne(inversedBy: 'flashcardModifications')]
    private ?Flashcard $flashcard = null;

    /** Le paquet auquel apprtient la carte à qui apprtient les modif */
    #[ORM\ManyToOne(inversedBy: 'flashcardModifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Deck $deck = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'furigana' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $furigana = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'exemple' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $example = null;


    #[ORM\Column(length: 60, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 60,
        maxMessage: "Le champ 'onyomi' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $onyomi = null;

    #[ORM\Column(length: 60, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 60,
        maxMessage: "Le champ 'kunyomi' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $kunyomi = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'recto' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $front = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'verso' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $back = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'moyen mnemotechnique' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $mnemotic = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'construction du point de grammaire' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $construction = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'notes de grammaire' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $grammarnotes = null;

    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'synonyme' ne peut pas faire plus de {{ limit }} caractères",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $synonym = null;

    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'antonyme' ne peut pas faire plus de {{ limit }} caractères",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $antonym = null;

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

    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    public function setDeck(?Deck $deck): static
    {
        $this->deck = $deck;

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

    public function getOnyomi(): ?string
    {
        return $this->onyomi;
    }

    public function setOnyomi(?string $onyomi): static
    {
        $this->onyomi = $onyomi;

        return $this;
    }

    public function getKunyomi(): ?string
    {
        return $this->kunyomi;
    }

    public function setKunyomi(?string $kunyomi): static
    {
        $this->kunyomi = $kunyomi;

        return $this;
    }

    public function getFront(): ?string
    {
        return $this->front;
    }

    public function setFront(?string $front): static
    {
        $this->front = $front;

        return $this;
    }

    public function getBack(): ?string
    {
        return $this->back;
    }

    public function setBack(?string $back): static
    {
        $this->back = $back;

        return $this;
    }

    public function getMnemotic(): ?string
    {
        return $this->mnemotic;
    }

    public function setMnemotic(?string $mnemotic): static
    {
        $this->mnemotic = $mnemotic;

        return $this;
    }

    public function getConstruction(): ?string
    {
        return $this->construction;
    }

    public function setConstruction(?string $construction): static
    {
        $this->construction = $construction;

        return $this;
    }

    public function getGrammarnotes(): ?string
    {
        return $this->grammarnotes;
    }

    public function setGrammarnotes(?string $grammarnotes): static
    {
        $this->grammarnotes = $grammarnotes;

        return $this;
    }

    public function getSynonym(): ?string
    {
        return $this->synonym;
    }

    public function setSynonym(?string $synonym): static
    {
        $this->synonym = $synonym;

        return $this;
    }

    public function getAntonym(): ?string
    {
        return $this->antonym;
    }

    public function setAntonym(?string $antonym): static
    {
        $this->antonym = $antonym;

        return $this;
    }
}
