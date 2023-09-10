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

    #[ORM\ManyToOne(inversedBy: 'flashcardModifications')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'flashcardModifications')]
    private ?Flashcard $flashcard = null;

    #[ORM\ManyToOne(inversedBy: 'flashcardModifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Deck $deck = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'traduction' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $translation = null;

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

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme polie' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $polite = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme negative' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $negative = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme conditionnelle en Ba' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $conditionnalBa = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme conditionnelle en Tara' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $conditionnalTara = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme imperative' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $imperative = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme volitionnelle' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $volitional = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme polie' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $causative = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme potentielle' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $potential = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme en te' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $teForm = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme en ta' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $taForm = null;

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

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 10,
        maxMessage: "Le champ 'kanji' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $kanji = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([ "getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'point de grammaire' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $grammarPoint = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'règle de grammaire' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $grammarRule = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 10,
        maxMessage: "Le champ 'mot' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $word = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'image' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getFlashcardModif"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'audio' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $audio = null;

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

    public function getTranslation(): ?string
    {
        return $this->translation;
    }

    public function setTranslation(?string $translation): static
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

    public function setExample(?string $example): static
    {
        $this->example = $example;

        return $this;
    }

    public function getPolite(): ?string
    {
        return $this->polite;
    }

    public function setPolite(?string $polite): static
    {
        $this->polite = $polite;

        return $this;
    }

    public function getNegative(): ?string
    {
        return $this->negative;
    }

    public function setNegative(?string $negative): static
    {
        $this->negative = $negative;

        return $this;
    }

    public function getConditionnalBa(): ?string
    {
        return $this->conditionnalBa;
    }

    public function setConditionnalBa(?string $conditionnalBa): static
    {
        $this->conditionnalBa = $conditionnalBa;

        return $this;
    }

    public function getConditionnalTara(): ?string
    {
        return $this->conditionnalTara;
    }

    public function setConditionnalTara(?string $conditionnalTara): static
    {
        $this->conditionnalTara = $conditionnalTara;

        return $this;
    }

    public function getImperative(): ?string
    {
        return $this->imperative;
    }

    public function setImperative(?string $imperative): static
    {
        $this->imperative = $imperative;

        return $this;
    }

    public function getVolitional(): ?string
    {
        return $this->volitional;
    }

    public function setVolitional(?string $volitional): static
    {
        $this->volitional = $volitional;

        return $this;
    }

    public function getCausative(): ?string
    {
        return $this->causative;
    }

    public function setCausative(?string $causative): static
    {
        $this->causative = $causative;

        return $this;
    }

    public function getPotential(): ?string
    {
        return $this->potential;
    }

    public function setPotential(?string $potential): static
    {
        $this->potential = $potential;

        return $this;
    }

    public function getTeForm(): ?string
    {
        return $this->teForm;
    }

    public function setTeForm(?string $teForm): static
    {
        $this->teForm = $teForm;

        return $this;
    }

    public function getTaForm(): ?string
    {
        return $this->taForm;
    }

    public function setTaForm(?string $taForm): static
    {
        $this->taForm = $taForm;

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

    public function getKanji(): ?string
    {
        return $this->kanji;
    }

    public function setKanji(?string $kanji): static
    {
        $this->kanji = $kanji;

        return $this;
    }

    public function getGrammarPoint(): ?string
    {
        return $this->grammarPoint;
    }

    public function setGrammarPoint(?string $grammarPoint): static
    {
        $this->grammarPoint = $grammarPoint;

        return $this;
    }

    public function getGrammarRule(): ?string
    {
        return $this->grammarRule;
    }

    public function setGrammarRule(?string $grammarRule): static
    {
        $this->grammarRule = $grammarRule;

        return $this;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(?string $word): static
    {
        $this->word = $word;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getAudio(): ?string
    {
        return $this->audio;
    }

    public function setAudio(?string $audio): static
    {
        $this->audio = $audio;

        return $this;
    }
}
